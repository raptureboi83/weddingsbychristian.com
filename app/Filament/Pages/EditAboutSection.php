<?php

namespace App\Filament\Pages;

use App\Models\AboutSection;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
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

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

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

                        FileUpload::make('image_path')
                            ->label('About Image')
                            ->disk('public')
                            ->directory('about/images')
                            ->image()
                            ->visible(fn (callable $get) => $get('media_type') === 'image')
                            ->columnSpanFull(),

                        FileUpload::make('video_path')
                            ->label('About Video')
                            ->disk('public')
                            ->directory('about/videos')
                            ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/webm'])
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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
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