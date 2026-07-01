<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Filament\Forms\Components\VisualMediaPicker;
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

    protected static string|\UnitEnum|null $navigationGroup = 'Page Settings';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public SiteSetting $record;

    public function mount(): void
    {
        $this->record = SiteSetting::current();

        $this->form->fill($this->record->toArray());
    }

    public function getSubheading(): ?string
    {
        return 'Manage the general website settings used across the site, including branding, social links, SEO, and contact information.';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Brand')
                    ->description('These settings control your main brand name, tagline, and logo details used across the website.')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Website name')
                            ->helperText('The main name of your website or business.')
                            ->placeholder('Weddings By Christian')
                            ->maxLength(255),

                        TextInput::make('site_tagline')
                            ->label('Website tagline')
                            ->helperText('A short sentence that describes your brand or service.')
                            ->placeholder('Cinematic wedding films with heart')
                            ->maxLength(255),

                        TextInput::make('logo_text')
                            ->label('Logo text')
                            ->helperText('Text version of your logo, if your design uses one.')
                            ->placeholder('Weddings By Christian')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        VisualMediaPicker::make('logo_image_path', 'Logo image', 'logos', imageOnly: true)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Social Links')
                    ->description('Social media links shown in both the site navigation and footer.')
                    ->schema([
                        TextInput::make('nav_facebook_url')
                            ->label('Facebook link')
                            ->helperText('The Facebook page link.')
                            ->placeholder('https://facebook.com/yourpage')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('nav_instagram_url')
                            ->label('Instagram link')
                            ->helperText('The Instagram profile link.')
                            ->placeholder('https://instagram.com/yourprofile')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('nav_youtube_url')
                            ->label('YouTube link')
                            ->helperText('The YouTube channel or video link.')
                            ->placeholder('https://youtube.com/yourchannel')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('nav_tiktok_url')
                            ->label('TikTok link')
                            ->helperText('The TikTok profile link.')
                            ->placeholder('https://tiktok.com/@yourprofile')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Footer')
                    ->description('These settings control the text shown in the website footer.')
                    ->schema([
                        TextInput::make('footer_title')
                            ->label('Footer heading')
                            ->helperText('The main heading shown in the footer.')
                            ->placeholder('Weddings By Christian')
                            ->maxLength(255),

                        TextInput::make('footer_copyright_text')
                            ->label('Copyright text')
                            ->helperText('The copyright line shown at the bottom of the site.')
                            ->placeholder('© 2026 Weddings By Christian. All rights reserved.')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('SEO')
                    ->description('These settings help search engines and social media understand how your website should appear when shared.')
                    ->schema([
                        TextInput::make('seo_meta_title')
                            ->label('SEO title')
                            ->helperText('The title used in search results and browser tabs.')
                            ->placeholder('Weddings By Christian | Wedding Videography')
                            ->maxLength(255),

                        Textarea::make('seo_meta_description')
                            ->label('SEO description')
                            ->helperText('A short summary used by search engines and social previews.')
                            ->placeholder('Wedding videography for couples who want an emotional, cinematic film that preserves the feeling of the day.')
                            ->rows(4)
                            ->columnSpanFull(),

                        VisualMediaPicker::make('seo_og_image_path', 'Social share image', 'seo', imageOnly: true)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Contact')
                    ->description('These settings control the main contact details used across the site and in the contact form.')
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('Public contact email')
                            ->helperText('The email address shown publicly on the website.')
                            ->placeholder('hello@weddingsbychristian.com')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('contact_phone')
                            ->label('Public contact phone')
                            ->helperText('The phone number shown publicly on the website.')
                            ->placeholder('(555) 555-5555')
                            ->maxLength(255),

                        TextInput::make('contact_based_in')
                            ->label('Based in')
                            ->helperText('Your location or the area you are based in.')
                            ->placeholder('Charleston, South Carolina')
                            ->maxLength(255),

                        TextInput::make('contact_form_recipient_email')
                            ->label('Contact form recipient email')
                            ->helperText('Contact form submissions will be sent to this email address.')
                            ->placeholder('hello@weddingsbychristian.com')
                            ->email()
                            ->maxLength(255),

                        VisualMediaPicker::make('email_logo_path', 'Email logo', 'email-logos', imageOnly: true)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
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