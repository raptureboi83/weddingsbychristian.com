<?php

namespace App\Filament\Pages;

use App\Models\FilmsSection;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class EditFilmsSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedFilm;

    protected static ?string $navigationLabel = 'Films Section';

    protected static ?string $title = 'Films Section';

    protected static string|\UnitEnum|null $navigationGroup = 'Page Sections';

    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    protected string $view = 'filament.pages.edit-films-section';

    public ?array $data = [];

    public FilmsSection $record;

    public function mount(): void
    {
        $this->record = FilmsSection::current();

        $this->form->fill($this->record->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Section Content')
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
                    ])
                    ->columns(2),

                Section::make('Publishing')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Show this section on the website')
                            ->default(true),
                    ]),
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
