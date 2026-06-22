<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public SiteSetting $record;

    public function mount(): void
    {
        $this->record = SiteSetting::current();

        $this->form->fill($this->record->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Brand')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Site Name')
                            ->maxLength(255),

                        TextInput::make('site_tagline')
                            ->label('Site Tagline')
                            ->maxLength(255),

                        TextInput::make('logo_text')
                            ->label('Logo Text')
                            ->maxLength(255),

                        TextInput::make('logo_image_path')
                            ->label('Logo Image Path')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Navigation Social Links')
                    ->schema([
                        TextInput::make('nav_facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('nav_instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('nav_youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('nav_tiktok_url')
                            ->label('TikTok URL')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Footer')
                    ->schema([
                        TextInput::make('footer_title')
                            ->label('Footer Title')
                            ->maxLength(255),

                        TextInput::make('footer_copyright_text')
                            ->label('Copyright Text')
                            ->maxLength(255),

                        TextInput::make('footer_facebook_url')
                            ->label('Footer Facebook URL')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('footer_instagram_url')
                            ->label('Footer Instagram URL')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('footer_youtube_url')
                            ->label('Footer YouTube URL')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('footer_tiktok_url')
                            ->label('Footer TikTok URL')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),

                        Textarea::make('seo_meta_description')
                            ->label('Meta Description')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('seo_og_image_path')
                            ->label('OG Image Path')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Contact')
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('Contact Email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('contact_phone')
                            ->label('Contact Phone')
                            ->maxLength(255),

                        TextInput::make('contact_based_in')
                            ->label('Based In')
                            ->maxLength(255),

                        TextInput::make('contact_form_recipient_email')
                            ->label('Form Recipient Email')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2),
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
            ->title('Site settings updated')
            ->success()
            ->send();
    }
}