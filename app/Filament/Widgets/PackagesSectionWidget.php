<?php

namespace App\Filament\Widgets;

use App\Models\PackagesSection;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Widgets\Widget;

class PackagesSectionWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.section-widget';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];

    public ?PackagesSection $record = null;

    public function mount(): void
    {
        $this->record = PackagesSection::current();
        $this->form->fill($this->record->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(1)
            ->components([
                Section::make('Packages Section Content')
                    ->description('Manage the content shown on the Packages page.')
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Header Content')
                            ->schema([
                                TextInput::make('eyebrow')
                                    ->maxLength(255),

                                TextInput::make('title')
                                    ->maxLength(255),

                                Textarea::make('description')
                                    ->rows(4)
                                    ->columnSpanFull(),

                                Textarea::make('intro')
                                    ->rows(6)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Section::make('Bottom Content')
                            ->schema([
                                TextInput::make('bottom_heading')
                                    ->label('Bottom Heading')
                                    ->maxLength(255),

                                Textarea::make('bottom_description')
                                    ->label('Bottom Description')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Section::make('Call To Action')
                            ->schema([
                                TextInput::make('cta_label')
                                    ->label('CTA Label')
                                    ->maxLength(255),

                                TextInput::make('cta_url')
                                    ->label('CTA URL')
                                    ->maxLength(255),
                            ])
                            ->columns(2),

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
            ->title('Packages section updated')
            ->success()
            ->send();
    }
}
