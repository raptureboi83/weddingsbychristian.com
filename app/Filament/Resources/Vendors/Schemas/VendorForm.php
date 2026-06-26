<?php

namespace App\Filament\Resources\Vendors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vendor Details')
                    ->description('Add the main details for a preferred vendor shown on your website.')
                    ->schema([
                        Select::make('vendor_category_id')
                            ->label('Vendor category')
                            ->helperText('Choose the category this vendor belongs to.')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('name')
                            ->label('Vendor name')
                            ->helperText('The business name visitors will see.')
                            ->placeholder('Coastal Events Photography')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('URL name')
                            ->helperText('Used in the page link. This is usually filled automatically from the vendor name.')
                            ->placeholder('coastal-events-photography')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Textarea::make('description')
                            ->label('Description')
                            ->helperText('Optional text describing this vendor or why you recommend them.')
                            ->placeholder('A talented team known for timeless imagery and a calm presence on the wedding day.')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Links')
                    ->description('Add any website or social links you want visitors to use.')
                    ->schema([
                        TextInput::make('website_url')
                            ->label('Website link')
                            ->helperText('The vendor’s main website address.')
                            ->placeholder('https://example.com')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('instagram_url')
                            ->label('Instagram link')
                            ->helperText('The vendor’s Instagram profile link.')
                            ->placeholder('https://instagram.com/...')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('facebook_url')
                            ->label('Facebook link')
                            ->helperText('The vendor’s Facebook page link.')
                            ->placeholder('https://facebook.com/...')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('tiktok_url')
                            ->label('TikTok link')
                            ->helperText('The vendor’s TikTok profile link.')
                            ->placeholder('https://tiktok.com/@...')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('youtube_url')
                            ->label('YouTube link')
                            ->helperText('The vendor’s YouTube channel or video link.')
                            ->placeholder('https://youtube.com/...')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Contact')
                    ->description('Add basic contact details for this vendor.')
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('Contact email')
                            ->helperText('An email address for this vendor.')
                            ->placeholder('hello@example.com')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('contact_phone')
                            ->label('Contact phone')
                            ->helperText('A phone number for this vendor.')
                            ->placeholder('(555) 555-5555')
                            ->maxLength(255),

                        TextInput::make('location')
                            ->label('Location')
                            ->helperText('City, state, or area where this vendor works.')
                            ->placeholder('Charleston, SC')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Media')
                    ->description('Add image paths if your site uses stored logo or cover images for vendors.')
                    ->schema([
                        TextInput::make('logo_path')
                            ->label('Logo file path')
                            ->helperText('The saved path to this vendor’s logo image, if used.')
                            ->placeholder('vendors/logos/vendor-logo.png')
                            ->maxLength(255),

                        TextInput::make('cover_image_path')
                            ->label('Cover image file path')
                            ->helperText('The saved path to this vendor’s cover image, if used.')
                            ->placeholder('vendors/covers/vendor-cover.jpg')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Visibility')
                    ->description('Control where this vendor appears and whether it is visible on the website.')
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Display order')
                            ->helperText('Lower numbers appear first.')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_published')
                            ->label('Show this vendor on the website')
                            ->helperText('Turn this off to hide this vendor from the live site.')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Feature this vendor')
                            ->helperText('Turn this on to highlight this vendor in featured areas.')
                            ->default(false),
                    ])
                    ->columns(3),
            ]);
    }
}