<?php

namespace App\Filament\Pages;

use App\Models\ContactSection;
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

    protected static string|\UnitEnum|null $navigationGroup = 'Page Sections';

    protected static ?int $navigationSort = 14;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    protected string $view = 'filament.pages.edit-contact-section';

    public ?array $data = [];

    public ContactSection $record;

    public function mount(): void
    {
        $this->record = ContactSection::current();

        $this->form->fill($this->record->toArray());
    }

    public function getSubheading(): ?string
    {
        return 'Edit the text shown in the website contact section, including the main intro, the form area, contact details area, and the final call-to-action button.';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Section Content')
                    ->description('This is the main heading area for the Contact section on the website.')
                    ->schema([
                        TextInput::make('eyebrow')
                            ->label('Small heading')
                            ->helperText('A short line shown above the main contact heading.')
                            ->placeholder('Contact')
                            ->maxLength(255),

                        TextInput::make('title')
                            ->label('Main heading')
                            ->helperText('The main title shown in the Contact section.')
                            ->placeholder('Let’s tell your story')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Intro text')
                            ->helperText('A short paragraph introducing the contact section.')
                            ->placeholder('Use this area to invite couples to reach out and share a little about their day.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Form Content')
                    ->description('This text appears near the contact form itself.')
                    ->schema([
                        TextInput::make('form_heading')
                            ->label('Form heading')
                            ->helperText('A heading shown above the contact form.')
                            ->placeholder('Start the conversation')
                            ->maxLength(255),

                        Textarea::make('form_description')
                            ->label('Form description')
                            ->helperText('Short text shown above or beside the form to encourage submissions.')
                            ->placeholder('Share a few details about your wedding and I’ll be in touch soon.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Info Content')
                    ->description('This text appears in the contact information area, usually beside or near the form.')
                    ->schema([
                        TextInput::make('info_heading')
                            ->label('Info heading')
                            ->helperText('A heading for the contact details area.')
                            ->placeholder('Get in touch')
                            ->maxLength(255),

                        Textarea::make('info_description')
                            ->label('Info description')
                            ->helperText('A short paragraph shown with the contact information.')
                            ->placeholder('Add a short note here about response times, travel, or what couples can expect after reaching out.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Call To Action')
                    ->description('This button can send visitors to another page or action from the Contact section.')
                    ->schema([
                        TextInput::make('cta_label')
                            ->label('Button text')
                            ->helperText('The text shown on the call-to-action button.')
                            ->placeholder('View Packages')
                            ->maxLength(255),

                        TextInput::make('cta_url')
                            ->label('Button link')
                            ->helperText('Where the button should send visitors, such as /packages or /contact.')
                            ->placeholder('/packages')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Publishing')
                    ->description('Control whether this section is visible on the live website.')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Show this section on the website')
                            ->helperText('Turn this off to hide the Contact section from the live site.')
                            ->default(true),
                    ]),
            ]);
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