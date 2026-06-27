<?php

namespace App\Filament\Widgets;

use App\Models\VendorsSection;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class VendorsSectionWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.section-widget';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public ?VendorsSection $record = null;

    public function mount(): void
    {
        $this->record = VendorsSection::current();
        $this->form->fill($this->record->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(1)
            ->components([
                Section::make('Vendors Section Content')
                    ->description('Manage the content shown in the header of the Vendors page.')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Small heading')
                            ->helperText('A short line shown above the vendors heading.')
                            ->placeholder('Partners')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->label('Main heading')
                            ->helperText('The main title shown in the Vendors section.')
                            ->placeholder('Our Preferred Vendors')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Description')
                            ->helperText('Optional text describing the vendors section.')
                            ->placeholder('If you would like to connect with anyone, please click their name for a website.')
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
            ->title('Vendors section updated')
            ->success()
            ->send();
    }
}
