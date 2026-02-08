<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->latest()->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required','string','max:150'],
            'slug'  => ['nullable','string','max:180','unique:services,slug'],
            'short_description' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'icon' => ['nullable','string','max:100'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'cta_text' => ['nullable','string','max:50'],
            'cta_url'  => ['nullable','string','max:255'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_active'  => ['nullable','boolean'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['created_by'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => ['required','string','max:150'],
            'slug'  => ['nullable','string','max:180','unique:services,slug,' . $service->id],
            'short_description' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
            'icon' => ['nullable','string','max:100'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'cta_text' => ['nullable','string','max:50'],
            'cta_url'  => ['nullable','string','max:255'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_active'  => ['nullable','boolean'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Service deleted.');
    }
}
