<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\VendorCategory;
use App\Models\VendorsSection;
use Illuminate\View\View;

class VendorsController extends Controller
{
    public function index(): View
    {
        $siteSettings = SiteSetting::current();

        $vendorsSection = VendorsSection::query()
            ->where('is_published', true)
            ->first();

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

        return view('vendors.index', [
            'siteSettings' => $siteSettings,
            'vendorsSection' => $vendorsSection,
            'vendorCategories' => $vendorCategories,
        ]);
    }
}
