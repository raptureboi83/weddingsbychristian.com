<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\ContactSection;
use App\Models\Film;
use App\Models\HeroSection;
use App\Models\Package;
use App\Models\PackagesSection;
use App\Models\PackageSharedBlock;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\VendorCategory;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $siteSettings = SiteSetting::current();

        $heroSection = HeroSection::query()
            ->where('is_published', true)
            ->first();

        $aboutSection = AboutSection::query()
            ->where('is_published', true)
            ->first();

        $packagesSection = PackagesSection::query()
            ->where('is_published', true)
            ->first();

        $contactSection = ContactSection::query()
            ->where('is_published', true)
            ->first();

        $packages = Package::query()
            ->where('is_active', true)
            ->with([
                'items' => fn ($query) => $query->orderBy('sort_order'),
            ])
            ->orderBy('sort_order')
            ->get();

        $packageSharedBlocks = PackageSharedBlock::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $films = Film::query()
            ->homepagePreview()
            ->get();

        $testimonials = Testimonial::query()
            ->published()
            ->ordered()
            ->limit(3)
            ->get();

        $vendorCategories = VendorCategory::query()
            ->where('is_published', true)
            ->with([
                'vendors' => fn ($query) => $query
                    ->where('is_published', true)
                    ->orderBy('sort_order')
                    ->orderBy('name'),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('welcome', [
            'siteSettings' => $siteSettings,
            'heroSection' => $heroSection,
            'aboutSection' => $aboutSection,
            'packagesSection' => $packagesSection,
            'packages' => $packages,
            'packageSharedBlocks' => $packageSharedBlocks,
            'films' => $films,
            'testimonials' => $testimonials,
            'vendorCategories' => $vendorCategories,
            'contactSection' => $contactSection,
        ]);
    }
}