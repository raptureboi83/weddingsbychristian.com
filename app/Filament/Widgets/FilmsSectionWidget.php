<?php

namespace App\Filament\Widgets;

use App\Models\FilmsSection;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class FilmsSectionWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.section-widget';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public ?FilmsSection $record = null;

    public function mount(): void
    {
        $this->record = FilmsSection::current();
        $this->form->fill($this->record->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(1)
            ->components([
                Section::make('Films Section Content')
                    ->description('Manage the content shown in the header of the Films page.')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Small heading')
                            ->helperText('A short line shown above the films heading.')
                            ->placeholder('Selected Work')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->label('Main heading')
                            ->helperText('The main title shown in the Films section.')
                            ->placeholder('Featured Films')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Description')
                            ->helperText('Optional text describing the films section.')
                            ->rows(4)
                            ->columnSpanFull(),

                        Section::make('Right Side CTA')
                            ->description('Manage the right-side card content and links on the Films page.')
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('cta_title')
                                    ->label('CTA title')
                                    ->placeholder('Looking for your own film?')
                                    ->maxLength(255),

                                Textarea::make('cta_copy')
                                    ->label('CTA text')
                                    ->rows(4)
                                    ->columnSpanFull(),

                                TextInput::make('cta_primary_label')
                                    ->label('Primary button label')
                                    ->placeholder('Back to home')
                                    ->maxLength(255),

                                TextInput::make('cta_primary_url')
                                    ->label('Primary button URL')
                                    ->placeholder('/')
                                    ->maxLength(2048),

                                TextInput::make('cta_secondary_label')
                                    ->label('Secondary button label')
                                    ->placeholder('Reach out')
                                    ->maxLength(255),

                                TextInput::make('cta_secondary_url')
                                    ->label('Secondary button URL')
                                    ->placeholder('/contact')
                                    ->maxLength(2048),
                            ])
                            ->columns(2),

                        Toggle::make('is_published')
                            ->label('Show this section on the website')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $this->record->update($data);

        Notification::make()
            ->title('Films section updated')
            ->success()
            ->send();
    }
}
