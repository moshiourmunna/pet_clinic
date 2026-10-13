<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\Plan;
use App\Models\Post;
use App\Models\Service;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'services' => Service::active()->get(),
            'doctors' => Doctor::active()->take(4)->get(),
            'plans' => Plan::orderBy('sort')->take(3)->get(),
            'testimonials' => Testimonial::where('is_active', true)->latest()->take(3)->get(),
            'posts' => Post::published()->take(3)->get(),
            'faqs' => Faq::orderBy('sort')->take(5)->get(),
        ]);
    }

    public function about()
    {
        return view('pages.about', ['doctors' => Doctor::active()->take(4)->get()]);
    }

    public function services()
    {
        return view('pages.services', ['services' => Service::active()->get()]);
    }

    public function pricing()
    {
        return view('pages.pricing', ['plans' => Plan::orderBy('sort')->get(), 'services' => Service::active()->get()]);
    }

    public function gallery()
    {
        return view('pages.gallery', ['images' => GalleryImage::orderBy('sort')->paginate(16)]);
    }

    public function faq()
    {
        return view('pages.faq', ['faqs' => Faq::orderBy('sort')->get()]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function sitemap()
    {
        $urls = collect(['home', 'about', 'services', 'doctors.index', 'appointment.create', 'pricing', 'gallery', 'blog.index', 'faq', 'contact'])
            ->map(fn ($r) => ['loc' => route($r), 'lastmod' => now()->toDateString()]);
        Doctor::active()->get()->each(fn ($d) => $urls->push(['loc' => route('doctors.show', $d), 'lastmod' => $d->updated_at->toDateString()]));
        Post::published()->get()->each(fn ($p) => $urls->push(['loc' => route('blog.show', $p), 'lastmod' => $p->updated_at->toDateString()]));

        return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
    }
}
