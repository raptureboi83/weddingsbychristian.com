<?php

namespace App\Filament\Widgets;

use App\Models\ContactSubmission;
use App\Models\Film;
use App\Models\MediaFile;
use App\Models\Package;
use App\Models\Testimonial;
use App\Models\Vendor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Films', Film::count())
                ->icon('heroicon-o-film')
                ->color('primary'),

            Stat::make('Packages', Package::count())
                ->icon('heroicon-o-gift')
                ->color('success'),

            Stat::make('Testimonials', Testimonial::count())
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('warning'),

            Stat::make('Vendors', Vendor::count())
                ->icon('heroicon-o-building-office')
                ->color('info'),

            Stat::make('Contact Submissions', ContactSubmission::count())
                ->icon('heroicon-o-inbox')
                ->color('gray'),

            Stat::make('Media Files', MediaFile::count())
                ->icon('heroicon-o-photo')
                ->color('danger'),
        ];
    }
}
