<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\TestimonialsSection;
use Illuminate\View\View;

class TestimonialsController extends Controller
{
    public function index(): View
    {
        $siteSettings = SiteSetting::current();

        $testimonialsSection = TestimonialsSection::query()
            ->where('is_published', true)
            ->first();

        $testimonials = Testimonial::query()
            ->published()
            ->ordered()
            ->get();

        return view('testimonials.index', [
            'siteSettings' => $siteSettings,
            'testimonialsSection' => $testimonialsSection,
            'testimonials' => $testimonials,
        ]);
    }
}
