<?php

namespace App\Filament\Widgets;

use App\Models\TestimonialsSection;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class TestimonialsSectionWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.section-widget';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public ?TestimonialsSection $record = null;

    public function mount(): void
    {
        $this->record = TestimonialsSection::current();
        $this->form->fill($this->record->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(1)
            ->components([
                Section::make('Testimonials Section Content')
                    ->description('Manage the content shown in the header of the Testimonials page.')
                    ->columnSpanFull()
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
            ->title('Testimonials section updated')
            ->success()
            ->send();
    }
}
