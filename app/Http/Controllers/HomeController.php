<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\ContactSection;
use App\Models\Film;
use App\Models\FilmsSection;
use App\Models\HeroSection;
use App\Models\Package;
use App\Models\PackagesSection;
use App\Models\PackageSharedBlock;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\TestimonialsSection;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Models\VendorsSection;
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

        $filmsSection = FilmsSection::query()
            ->where('is_published', true)
            ->first();

        $testimonialsSection = TestimonialsSection::query()
            ->where('is_published', true)
            ->first();

        $vendorsSection = VendorsSection::query()
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
            ->featured()
            ->ordered()
            ->limit(3)
            ->get();

        $vendorPreview = Vendor::query()
            ->where('vendors.is_published', true)
            ->select('vendors.*')
            ->join('vendor_categories', 'vendor_categories.id', '=', 'vendors.vendor_category_id')
            ->where('vendor_categories.is_published', true)
            ->with(['category'])
            ->orderBy('vendor_categories.sort_order')
            ->orderBy('vendor_categories.name')
            ->orderBy('vendors.sort_order')
            ->orderBy('vendors.name')
            ->limit(3)
            ->get();

        return view('welcome', [
            'siteSettings' => $siteSettings,
            'heroSection' => $heroSection,
            'aboutSection' => $aboutSection,
            'filmsSection' => $filmsSection,
            'packagesSection' => $packagesSection,
            'testimonialsSection' => $testimonialsSection,
            'vendorsSection' => $vendorsSection,
            'packages' => $packages,
            'packageSharedBlocks' => $packageSharedBlocks,
            'films' => $films,
            'testimonials' => $testimonials,
            'vendorPreview' => $vendorPreview,
            'contactSection' => $contactSection,
        ]);
    }
}