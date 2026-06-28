<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\View\View;

class TestimonialsController extends Controller
{
    public function index(): View
    {
        $siteSettings = SiteSetting::current();

        $testimonials = Testimonial::query()
            ->published()
            ->ordered()
            ->get();

        return view('testimonials.index', [
            'siteSettings' => $siteSettings,
            'testimonials' => $testimonials,
        ]);
    }
}
