<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class FrontPagesController extends Controller
{
    /**
     * Home page
     */
    public function home()
    {
        $services = \App\Models\Service::where('is_active', true)
            ->inRandomOrder()
            ->get();

        return view('pages.home', compact('services'));
    }

    /**
     * About page
     */
    public function about()
    {
        $services = \App\Models\Service::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.about', compact("services"));
    }

    /**
     * Services page
     */
    public function services()
    {


        $services = \App\Models\Service::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.services', compact('services'));
    }

    /**
     * Blog listing page
     */
    // public function blog()
    // {
    //     $services = \App\Models\Service::where('is_active', true)
    //         ->orderBy('sort_order')
    //         ->take(6)
    //         ->get();
    //     return view('pages.blog.index', compact("services"));
    // }

    /**
     * Contact page
     */
    public function contact()
    {
        $services = \App\Models\Service::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.contact', compact("services"));
    }

    /**
     * Contact form submission (optional)
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:60'],
            'last_name'  => ['required', 'string', 'max:60'],
            'email'      => ['required', 'email', 'max:120'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'message'    => ['required', 'string', 'max:2000'],
        ]);

        // TODO: send email or store to DB (later)
        // For now, just flash success
        return back()->with('success', 'Thanks! We have received your message.');
    }

    public function howitworks()
    {

        $services = \App\Models\Service::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        return view('pages.home', compact('services'));
    }

    public function showservice($slug)
    {

        $service = Service::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        // Optional: Load related services for sidebar
        $relatedServices = Service::where('is_active', 1)
            ->where('id', '!=', $service->id)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        return view('pages.service', compact('service', 'relatedServices'));
    }
}
