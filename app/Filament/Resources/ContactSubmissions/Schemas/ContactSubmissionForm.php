<?php

namespace App\Filament\Resources\ContactSubmissions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact Details')
                    ->description('Basic contact information submitted by the couple.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full name')
                            ->helperText('The name of the person who submitted the form.')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email address')
                            ->helperText('The best email address to reply to.')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Phone number')
                            ->helperText('Optional phone number for follow-up.')
                            ->maxLength(255),

                        TextInput::make('partner_name')
                            ->label('Partner name')
                            ->helperText('The name of their fiancé, fiancée, or partner.')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Wedding Details')
                    ->description('Wedding information shared in the inquiry.')
                    ->schema([
                        DatePicker::make('wedding_date')
                            ->label('Wedding date')
                            ->helperText('The date they entered for the wedding.'),

                        TextInput::make('budget_range')
                            ->label('Budget range')
                            ->helperText('The budget range they selected or entered.')
                            ->maxLength(255),

                        TextInput::make('location')
                            ->label('Location')
                            ->helperText('The wedding location or area they mentioned.')
                            ->maxLength(255),

                        TextInput::make('venue')
                            ->label('Venue')
                            ->helperText('The venue name they entered, if provided.')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Inquiry')
                    ->description('Services requested and the couple’s message.')
                    ->schema([
                        Select::make('services_requested')
                            ->label('Services requested')
                            ->helperText('The services the couple selected on the contact form.')
                            ->multiple()
                            ->options([
                                'Wedding Film' => 'Wedding Film',
                                'Teaser Film' => 'Teaser Film',
                                'Speeches' => 'Speeches',
                                'Drone Coverage' => 'Drone Coverage',
                                'Full Ceremony Edit' => 'Full Ceremony Edit',
                                'Additional Coverage' => 'Additional Coverage',
                            ]),

                        TextInput::make('how_did_you_hear')
                            ->label('How did they hear about you?')
                            ->helperText('Where the couple said they found you.')
                            ->maxLength(255),

                        Textarea::make('message')
                            ->label('Message')
                            ->helperText('The full message submitted through the contact form.')
                            ->required()
                            ->rows(8)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Internal')
                    ->description('Private admin-only fields for tracking and follow-up.')
                    ->schema([
                        Select::make('status')
                            ->label('Inquiry status')
                            ->helperText('Use this to track where this lead is in your process.')
                            ->required()
                            ->options([
                                'new' => 'New',
                                'in_progress' => 'In Progress',
                                'replied' => 'Replied',
                                'booked' => 'Booked',
                                'archived' => 'Archived',
                            ])
                            ->default('new'),

                        TextInput::make('submitted_at')
                            ->label('Submitted at')
                            ->helperText('The date and time this inquiry was received.')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('admin_notes')
                            ->label('Private notes')
                            ->helperText('Internal notes only. This is never shown on the public website.')
                            ->rows(6)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

            ]);
    }
}