<?php

namespace App\Filament\Pages;

use App\Models\ContactSection;
use Filament\Actions\Action;
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

class EditContactSection extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Contact Section';

    protected static ?string $title = 'Contact Section';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.pages.edit-contact-section';

    public ?array $data = [];

    public ContactSection $record;

    public function mount(): void
    {
        $this->record = ContactSection::current();

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
                            ->maxLength(255),

                        TextInput::make('title')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Form Content')
                    ->schema([
                        TextInput::make('form_heading')
                            ->label('Form Heading')
                            ->maxLength(255),

                        Textarea::make('form_description')
                            ->label('Form Description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Info Content')
                    ->schema([
                        TextInput::make('info_heading')
                            ->label('Info Heading')
                            ->maxLength(255),

                        Textarea::make('info_description')
                            ->label('Info Description')
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
                            ->url()
                            ->maxLength(255),
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
            ->title('Contact section updated')
            ->success()
            ->send();
    }
}