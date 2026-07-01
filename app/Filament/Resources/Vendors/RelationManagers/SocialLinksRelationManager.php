<?php

namespace App\Filament\Resources\Vendors\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SocialLinksRelationManager extends RelationManager
{
    protected static string $relationship = 'socialLinks';

    protected static ?string $recordTitleAttribute = 'platform';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('platform')
            ->columns([
                Tables\Columns\TextColumn::make('platform')
                    ->label('Platform')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->url()
                    ->openUrlInNewTab()
                    ->limit(50)
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Forms\Components\Select::make('platform')
                            ->required()
                            ->searchable()
                            ->options([
                                'facebook' => 'Facebook',
                                'instagram' => 'Instagram',
                                'tiktok' => 'TikTok',
                                'youtube' => 'YouTube',
                                'twitter' => 'Twitter',
                                'linkedin' => 'LinkedIn',
                                'pinterest' => 'Pinterest',
                                'website' => 'Website',
                            ]),

                        Forms\Components\TextInput::make('url')
                            ->required()
                            ->url()
                            ->placeholder('https://example.com'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('platform')
                            ->required()
                            ->searchable()
                            ->options([
                                'facebook' => 'Facebook',
                                'instagram' => 'Instagram',
                                'tiktok' => 'TikTok',
                                'youtube' => 'YouTube',
                                'twitter' => 'Twitter',
                                'linkedin' => 'LinkedIn',
                                'pinterest' => 'Pinterest',
                                'website' => 'Website',
                            ]),

                        Forms\Components\TextInput::make('url')
                            ->required()
                            ->url()
                            ->placeholder('https://example.com'),
                    ]),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Forms\Components\Select::make('platform')
                            ->required()
                            ->searchable()
                            ->options([
                                'facebook' => 'Facebook',
                                'instagram' => 'Instagram',
                                'tiktok' => 'TikTok',
                                'youtube' => 'YouTube',
                                'twitter' => 'Twitter',
                                'linkedin' => 'LinkedIn',
                                'pinterest' => 'Pinterest',
                                'website' => 'Website',
                            ]),

                        Forms\Components\TextInput::make('url')
                            ->required()
                            ->url()
                            ->placeholder('https://example.com'),
                    ]),
            ]);
    }
}

