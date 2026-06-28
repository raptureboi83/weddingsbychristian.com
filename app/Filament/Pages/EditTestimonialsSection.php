<?php

namespace App\Filament\Pages;

use App\Models\TestimonialsSection;
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

class EditTestimonialsSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftEllipsis;

    protected static ?string $navigationLabel = 'Testimonials Section';

    protected static ?string $title = 'Testimonials Section';

    protected static string|\UnitEnum|null $navigationGroup = 'Page Sections';

    protected static ?int $navigationSort = 11;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    protected string $view = 'filament.pages.edit-testimonials-section';

    public ?array $data = [];

    public TestimonialsSection $record;

    public function mount(): void
    {
        $this->record = TestimonialsSection::current();

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
                            ->helperText('A short line shown above the testimonials heading.')
                            ->placeholder('Kind Words')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->label('Main heading')
                            ->helperText('The main title shown in the Testimonials section.')
                            ->placeholder('What Clients Are Saying')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Description')
                            ->helperText('Optional text describing the testimonials section.')
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
            ->title('Testimonials section updated')
            ->success()
            ->send();
    }
}
