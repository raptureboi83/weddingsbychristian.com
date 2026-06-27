<?php

namespace App\Filament\Pages;

use App\Models\AboutSection;
use App\Filament\Forms\Components\VisualMediaPicker;
use Filament\Forms\Components\Select;
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

class EditAboutSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?string $navigationLabel = 'About Section';

    protected static ?string $title = 'About Section';

    protected static string|\UnitEnum|null $navigationGroup = 'Page Settings';

    protected static ?int $navigationSort = 12;

    protected string $view = 'filament.pages.edit-about-section';

    public ?array $data = [];

    public AboutSection $record;

    public function mount(): void
    {
        $this->record = AboutSection::current();

        $this->form->fill($this->record->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('About Content')
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Small Title')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->label('Main Title')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(6)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Media')
                    ->schema([
                        Select::make('media_type')
                            ->options([
                                'image' => 'Image',
                                'video' => 'Video',
                            ])
                            ->default('image')
                            ->required()
                            ->live(),

                        VisualMediaPicker::make('image_path', 'About Image', 'about/images', imageOnly: true)
                            ->visible(fn (callable $get) => $get('media_type') === 'image')
                            ->columnSpanFull(),

                        VisualMediaPicker::make('video_path', 'About Video', 'about/videos', acceptedFileTypes: ['video/mp4', 'video/quicktime', 'video/webm'])
                            ->visible(fn (callable $get) => $get('media_type') === 'video')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

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
            ->title('About section updated')
            ->success()
            ->send();
    }
}