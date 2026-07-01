<?php

namespace App\Console\Commands;

use App\Models\ContactSubmission;
use App\Models\ContactSubmissionReply;
use Illuminate\Console\Command;

class CheckContactInbox extends Command
{
    protected $signature = 'inbox:check';
    protected $description = 'Check the contact inbox for client replies and log them';

    private ?string $lastImapError = null;

    public function handle(): int
    {
        gc_enable();

        $host = config('mail.imap_host');
        $port = config('mail.imap_port');
        $username = config('mail.imap_username');
        $password = config('mail.imap_password');
        $encryption = config('mail.imap_encryption', 'ssl');

        if (empty($host) || empty($username) || empty($password)) {
            $this->error('IMAP not configured. Set MAIL_IMAP_* in .env');
            return Command::FAILURE;
        }

        $mailbox = '{' . $host . ':' . $port . '/' . $encryption . '}INBOX';
        $inbox = $this->openInbox($mailbox, $username, $password);

        if ($inbox === false) {
            $this->error('Cannot connect: ' . ($this->lastImapError ?: 'Unknown IMAP connection error.'));
            return Command::FAILURE;
        }

        $seen = imap_search($inbox, 'UNSEEN');

        if ($seen === false) {
            $this->info('No unseen messages.');
            @imap_close($inbox);
            $this->clearImapErrorStack();
            return Command::SUCCESS;
        }

        $count = 0;

        foreach ($seen as $msg) {
            $headers = imap_headerinfo($inbox, $msg);

            $fromEmail = null;
            if (isset($headers->from[0])) {
                $fromEmail = $headers->from[0]->mailbox . '@' . $headers->from[0]->host;
            }

            $subject = isset($headers->subject) ? @imap_utf8($headers->subject) : '';
            $inReplyTo = isset($headers->in_reply_to) ? trim($headers->in_reply_to, '<> ') : null;

            if (!$fromEmail) {
                continue;
            }

            $body = $this->getPlainTextBody($inbox, $msg);
            if (!$body) {
                $body = '[No text content found]';
            }

            $submission = null;

            if ($inReplyTo) {
                $reply = ContactSubmissionReply::where('message_id', '<' . $inReplyTo . '>')
                    ->orWhere('message_id', $inReplyTo)
                    ->first();

                if ($reply) {
                    $submission = $reply->submission;
                }
            }

            if (!$submission) {
                $submission = ContactSubmission::where('email', $fromEmail)
                    ->latest()
                    ->first();
            }

            if (!$submission) {
                $this->warn("No matching submission for {$fromEmail}, skipping.");
                imap_setflag_full($inbox, $msg, '\\Seen \\Flagged');
                continue;
            }

            $existing = ContactSubmissionReply::where('contact_submission_id', $submission->id)
                ->where('sender_email', $fromEmail)
                ->where('body', $body)
                ->where('sender_type', 'client')
                ->first();

            if ($existing) {
                imap_setflag_full($inbox, $msg, '\\Seen');
                continue;
            }

            $body = $this->stripQuotedReply($body);

            ContactSubmissionReply::create([
                'contact_submission_id' => $submission->id,
                'user_id' => null,
                'sender_type' => 'client',
                'sender_name' => $fromEmail,
                'sender_email' => $fromEmail,
                'body' => $body,
                'message_id' => null,
            ]);

            imap_setflag_full($inbox, $msg, '\\Seen');
            $count++;

            if ($count % 5 === 0) {
                imap_gc($inbox, IMAP_GC_ELT | IMAP_GC_ENV);
                gc_collect_cycles();
            }
        }

        @imap_close($inbox);
        $this->clearImapErrorStack();

        $this->info("Processed {$count} new reply(ies).");
        return Command::SUCCESS;
    }

    private function openInbox(string $mailbox, string $username, string $password)
    {
        $this->lastImapError = null;
        $this->clearImapErrorStack();

        $attempts = 3;

        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            $inbox = @imap_open($mailbox, $username, $password);

            if ($inbox !== false) {
                $this->clearImapErrorStack();
                return $inbox;
            }

            $errors = function_exists('imap_errors') ? (imap_errors() ?: []) : [];
            $alerts = function_exists('imap_alerts') ? (imap_alerts() ?: []) : [];
            $lastError = imap_last_error();

            $combined = array_filter(array_merge($errors, $alerts));
            $this->lastImapError = $lastError ?: (!empty($combined) ? implode(' | ', $combined) : 'Unknown IMAP connection error.');

            if ($attempt < $attempts) {
                usleep(300000);
            }
        }

        $this->clearImapErrorStack();

        return false;
    }

    private function clearImapErrorStack(): void
    {
        if (function_exists('imap_errors')) {
            imap_errors();
        }

        if (function_exists('imap_alerts')) {
            imap_alerts();
        }
    }

    private function getPlainTextBody($inbox, int $msg): ?string
    {
        $structure = imap_fetchstructure($inbox, $msg);

        if ($structure && isset($structure->parts)) {
            $found = $this->findPart($inbox, $msg, $structure->parts, 'text/plain');
            if ($found) {
                return $found;
            }

            $body = imap_fetchbody($inbox, $msg, 1);
            if ($body !== false) {
                if (isset($structure->parts[0]) && $structure->parts[0]->encoding === 3) {
                    $body = base64_decode($body);
                } elseif (isset($structure->parts[0]) && $structure->parts[0]->encoding === 4) {
                    $body = quoted_printable_decode($body);
                }
                $charset = $this->detectCharset($structure->parts[0] ?? $structure);
                $decoded = $this->decodeText($body, $charset);
                $clean = trim($decoded);
                if (!empty($clean)) {
                    return $clean;
                }
            }
        }

        $body = imap_body($inbox, $msg);
        if ($body !== false) {
            $charset = $this->detectCharset($structure);
            $decoded = $this->decodeText($body, $charset);
            return trim($decoded);
        }

        return null;
    }

    private function findPart($inbox, int $msg, array $parts, string $mimeType, int $prefix = 0): ?string
    {
        foreach ($parts as $i => $part) {
            $num = $prefix > 0 ? $prefix . '.' . ($i + 1) : ($i + 1);

            if (isset($part->parts)) {
                $result = $this->findPart($inbox, $msg, $part->parts, $mimeType, $num);
                if ($result !== null) {
                    return $result;
                }
            }

            $type = $part->type ?? 0;
            $subtype = $part->subtype ?? '';
            $currentMime = $this->mimeType($type) . '/' . strtolower($subtype);

            if ($currentMime === $mimeType) {
                $body = imap_fetchbody($inbox, $msg, $num);

                if ($part->encoding === 3) {
                    $body = base64_decode($body);
                } elseif ($part->encoding === 4) {
                    $body = quoted_printable_decode($body);
                }

                $charset = $this->detectCharset($part);
                $decoded = $this->decodeText($body, $charset);
                $clean = trim($decoded);

                if (!empty($clean)) {
                    return $clean;
                }
            }
        }

        return null;
    }

    private function mimeType(int $type): string
    {
        $map = [
            0 => 'text',
            1 => 'multipart',
            2 => 'message',
            3 => 'application',
            4 => 'audio',
            5 => 'image',
            6 => 'video',
            7 => 'other',
        ];

        return $map[$type] ?? 'other';
    }

    private function detectCharset($part): string
    {
        if (isset($part->parameters)) {
            foreach ($part->parameters as $param) {
                if (strtolower($param->attribute) === 'charset') {
                    return strtolower($param->value);
                }
            }
        }

        if (isset($part->dparameters)) {
            foreach ($part->dparameters as $param) {
                if (strtolower($param->attribute) === 'charset') {
                    return strtolower($param->value);
                }
            }
        }

        return 'utf-8';
    }

    private function decodeText(string $text, string $charset): string
    {
        if (strtolower($charset) !== 'utf-8' && function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($text, 'utf-8', $charset);
            if ($converted !== false) {
                return $converted;
            }
        }

        return $text;
    }

    private function stripQuotedReply(string $body): string
    {
        $patterns = [
            '/^_{2,}.*$/m',
            '/\n_{2,}.*$/s',
            '/\nOn .+\n?.*\n?.*wrote:\n.*$/s',
            '/\n-+\s*Original Message\s*-+\n.*$/si',
            '/\nFrom: .+\nSent: .+\nTo: .+\nSubject: .+\n.*$/s',
            '/\n>.*$/s',
        ];

        foreach ($patterns as $pattern) {
            $cleaned = preg_replace($pattern, '', $body);
            if ($cleaned !== null && trim($cleaned) !== '') {
                $body = trim($cleaned);
            }
        }

        return trim($body);
    }
}
