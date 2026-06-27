<?php

namespace App\Filament\Pages;

use App\Models\HeroSection;
use App\Filament\Forms\Components\VisualMediaPicker;
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

class EditHeroSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $navigationLabel = 'Hero Section';

    protected static ?string $title = 'Hero Section';

    protected static string|\UnitEnum|null $navigationGroup = 'Page Settings';

    protected static ?int $navigationSort = 11;

    protected string $view = 'filament.pages.edit-hero-section';

    public ?array $data = [];

    public HeroSection $record;

    public function mount(): void
    {
        $this->record = HeroSection::current();

        $this->form->fill($this->record->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Hero Content')
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Small Title')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->label('Main Title')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Background Media')
                    ->schema([
                        VisualMediaPicker::make('background_media_path', 'Background Image / Media', 'hero')
                            ->columnSpanFull(),

                        VisualMediaPicker::make('background_fallback_image_path', 'Fallback Image (if video)', 'hero/fallback', imageOnly: true)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Section::make('Publishing')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()
            ->title('Hero section updated')
            ->success()
            ->send();
    }
}