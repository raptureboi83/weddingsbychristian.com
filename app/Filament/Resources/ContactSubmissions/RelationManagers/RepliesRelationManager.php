<?php

namespace App\Filament\Resources\ContactSubmissions\RelationManagers;

use App\Mail\ContactReplyMail;
use App\Models\ContactSubmissionReply;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RepliesRelationManager extends RelationManager
{
    protected static string $relationship = 'replies';

    protected static ?string $title = 'Conversation Thread';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender_name')
                    ->label('From')
                    ->weight(fn ($record) => $record->sender_type === 'admin' ? 'bold' : 'normal')
                    ->color(fn ($record) => $record->sender_type === 'admin' ? 'primary' : 'gray'),

                TextColumn::make('body')
                    ->label('Message')
                    ->wrap(),

                TextColumn::make('sender_type')
                    ->label('')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'admin' ? 'Admin' : 'Client')
                    ->color(fn ($state) => $state === 'admin' ? 'success' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Sent')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Action::make('refresh')
                    ->label('Refresh')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('gray')
                    ->action(function () {
                        $this->dispatch('$refresh');
                        Notification::make()
                            ->title('Conversation refreshed')
                            ->success()
                            ->send();
                    }),

                Action::make('sendReply')
                    ->label('Send Reply')
                    ->icon(Heroicon::OutlinedPaperAirplane)
                    ->color('primary')
                    ->form([
                        Textarea::make('body')
                            ->label('Your reply')
                            ->helperText('This will be emailed to the couple.')
                            ->required()
                            ->rows(6)
                            ->placeholder('Hi there! Thanks for reaching out...'),
                    ])
                    ->action(function (array $data) {
                        $submission = $this->getOwnerRecord();
                        $user = Auth::user();

                        $reply = ContactSubmissionReply::create([
                            'contact_submission_id' => $submission->id,
                            'user_id' => $user?->id,
                            'sender_type' => 'admin',
                            'sender_name' => $user?->name ?? 'Admin',
                            'sender_email' => $user?->email ?? config('mail.from.address'),
                            'body' => $data['body'],
                        ]);

                        $mail = new ContactReplyMail($reply);
                        $mail->messageId = '<reply-' . $reply->id . '-' . $submission->id . '@weddingsbychristian.com>';

                        $reply->update(['message_id' => $mail->messageId]);

                        Mail::to($submission->email)
                            ->send($mail);

                        if ($submission->status === 'new') {
                            $submission->update(['status' => 'replied']);
                        }
                    }),
            ])
            ->actions([
                Action::make('replyTo')
                    ->label('Reply')
                    ->icon(Heroicon::OutlinedPaperAirplane)
                    ->color('primary')
                    ->visible(fn ($record) => $record->sender_type === 'client')
                    ->modalHeading('Reply to Client')
                    ->modalWidth('2xl')
                    ->form(function ($record) {
                        $replies = $this->getOwnerRecord()->replies()
                            ->orderBy('created_at', 'desc')
                            ->get();

                        return [
                            Textarea::make('body')
                                ->label('Your reply')
                                ->helperText('This will be emailed to the couple.')
                                ->required()
                                ->rows(6)
                                ->placeholder('Hi there! Thanks for reaching out...'),

                            ViewField::make('conversation')
                                ->label('Conversation History')
                                ->view('admin.conversation-thread')
                                ->viewData([
                                    'replies' => $replies,
                                    'highlightId' => $record->id,
                                ]),
                        ];
                    })
                    ->action(function (array $data, $record) {
                        $submission = $this->getOwnerRecord();
                        $user = Auth::user();

                        $reply = ContactSubmissionReply::create([
                            'contact_submission_id' => $submission->id,
                            'user_id' => $user?->id,
                            'sender_type' => 'admin',
                            'sender_name' => $user?->name ?? 'Admin',
                            'sender_email' => $user?->email ?? config('mail.from.address'),
                            'body' => $data['body'],
                        ]);

                        $mail = new ContactReplyMail($reply);
                        $mail->messageId = '<reply-' . $reply->id . '-' . $submission->id . '@weddingsbychristian.com>';

                        $reply->update(['message_id' => $mail->messageId]);

                        Mail::to($submission->email)
                            ->send($mail);

                        if ($submission->status === 'new') {
                            $submission->update(['status' => 'replied']);
                        }
                    }),
            ]);
    }
}
