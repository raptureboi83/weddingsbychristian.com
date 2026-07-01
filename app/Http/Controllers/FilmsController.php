<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\FilmsSection;
use App\Models\SiteSetting;
use Illuminate\View\View;

class FilmsController extends Controller
{
    public function index(): View
    {
        $siteSettings = SiteSetting::current();
        $filmsSection = FilmsSection::current();

        $featuredFilms = Film::query()
            ->homepagePreview()
            ->get();

        $archiveFilms = Film::query()
            ->archiveListing()
            ->get();

        return view('films.index', [
            'siteSettings' => $siteSettings,
            'filmsSection' => $filmsSection,
            'featuredFilms' => $featuredFilms,
            'archiveFilms' => $archiveFilms,
        ]);
    }

    public function show(Film $film): View
    {
        abort_unless($film->is_published, 404);

        $siteSettings = SiteSetting::current();

        return view('films.show', [
            'siteSettings' => $siteSettings,
            'film' => $film,
        ]);
    }
}
