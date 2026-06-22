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
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->maxLength(255),

                        TextInput::make('partner_name')
                            ->label('Partner Name')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Wedding Details')
                    ->schema([
                        DatePicker::make('wedding_date'),

                        TextInput::make('budget_range')
                            ->maxLength(255),

                        TextInput::make('location')
                            ->maxLength(255),

                        TextInput::make('venue')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Inquiry')
                    ->schema([
                        Select::make('services_requested')
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
                            ->label('How Did You Hear About Me?')
                            ->maxLength(255),

                        Textarea::make('message')
                            ->required()
                            ->rows(8)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Internal')
                    ->schema([
                        Select::make('status')
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
                            ->label('Submitted At')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('admin_notes')
                            ->rows(6)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}