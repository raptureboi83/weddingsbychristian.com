<?php

namespace App\Console\Commands;

use App\Models\ContactSubmission;
use App\Models\ContactSubmissionReply;
use Illuminate\Console\Command;

class ReceiveInboundEmail extends Command
{
    protected $signature = 'inbox:receive';
    protected $description = 'Process a piped inbound email from stdin and log it as a client reply';

    public function handle(): int
    {
        $raw = stream_get_contents(\STDIN);

        if (empty($raw)) {
            $this->error('No email content received on stdin.');
            return Command::FAILURE;
        }

        $headers = $this->parseHeaders($raw);
        $body = $this->extractPlainText($raw);

        $fromEmail = $headers['from'] ?? null;
        $inReplyTo = $headers['in-reply-to'] ?? $headers['references'] ?? null;

        if (!$fromEmail) {
            $this->error('No sender found in email.');
            return Command::FAILURE;
        }

        if (!$body) {
            $body = '[No text content found]';
        }

        $submission = null;

        if ($inReplyTo) {
            $reply = ContactSubmissionReply::where('message_id', $this->normalizeMessageId($inReplyTo))->first();
            if ($reply) {
                $submission = $reply->submission;
            }
        }

        if (!$submission) {
            $submission = ContactSubmission::where('email', $fromEmail)->latest()->first();
        }

        if (!$submission) {
            $this->warn("No matching submission for {$fromEmail}, skipping.");
            return Command::SUCCESS;
        }

        $existing = ContactSubmissionReply::where('contact_submission_id', $submission->id)
            ->where('sender_email', $fromEmail)
            ->where('body', $body)
            ->where('sender_type', 'client')
            ->first();

        if ($existing) {
            $this->info('Duplicate reply, skipping.');
            return Command::SUCCESS;
        }

        ContactSubmissionReply::create([
            'contact_submission_id' => $submission->id,
            'user_id' => null,
            'sender_type' => 'client',
            'sender_name' => $fromEmail,
            'sender_email' => $fromEmail,
            'body' => $body,
            'message_id' => null,
        ]);

        $this->info("Logged reply from {$fromEmail} for submission #{$submission->id}.");
        return Command::SUCCESS;
    }

    private function parseHeaders(string $raw): array
    {
        $headers = [];
        $lines = explode("\n", $raw);

        foreach ($lines as $line) {
            if (trim($line) === '') {
                break;
            }

            if (preg_match('/^([\w\-]+):\s*(.*)/', $line, $m)) {
                $key = strtolower($m[1]);
                $headers[$key] = trim($m[2]);
            }
        }

        return $headers;
    }

    private function extractPlainText(string $raw): ?string
    {
        $parts = preg_split("/\r?\n\r?\n/", $raw, 2);
        $body = $parts[1] ?? '';

        $lines = explode("\n", $body);
        $text = [];
        $inQuote = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (preg_match('/^>+/', $trimmed)) {
                continue;
            }

            if (preg_match('/^On .+ wrote:$/', $trimmed)) {
                $inQuote = true;
                continue;
            }

            if ($inQuote && $trimmed === '') {
                continue;
            }

            if ($inQuote && !preg_match('/^On .+ wrote:$/', $trimmed)) {
                $inQuote = false;
            }

            if (!$inQuote) {
                $text[] = $line;
            }
        }

        $text = preg_replace('/-- \r?\n.*$/s', '', implode("\n", $text));

        return trim($text) ?: null;
    }

    private function normalizeMessageId(string $id): string
    {
        $id = trim($id);
        if (!str_starts_with($id, '<')) {
            $id = '<' . $id;
        }
        if (!str_ends_with($id, '>')) {
            $id = $id . '>';
        }
        return $id;
    }
}
