<?php

namespace App\Filament\Resources\Testimonials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                TextColumn::make('couple_names')
                    ->label('Couple')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('quote')
                    ->limit(80)
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('source_label')
                    ->label('Source')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('wedding_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                CheckboxColumn::make('is_featured')
                    ->label('Featured')
                    ->sortable(),

                CheckboxColumn::make('is_published')
                    ->label('Published')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Published'),

                TernaryFilter::make('is_featured')
                    ->label('Featured'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}