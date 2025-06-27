<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsAboutSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsAboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aboutSections = CmsAboutSection::orderBy('sort_order')->paginate(10);
        return view('admin.cms.about.index', compact('aboutSections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cms.about.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'section_type' => 'required|string|max:100',
            'sort_order' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Upload image if provided
        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('cms/about', 'public');
        }

        // Handle extra_data as JSON
        if ($request->has('extra_data') && is_array($request->extra_data)) {
            $data['extra_data'] = $request->extra_data;
        }

        CmsAboutSection::create($data);

        return redirect()->route('admin.cms.about.index')
            ->with('success', 'Section À propos créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CmsAboutSection $about)
    {
        return view('admin.cms.about.show', compact('about'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CmsAboutSection $about)
    {
        return view('admin.cms.about.edit', compact('about'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CmsAboutSection $about)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'section_type' => 'required|string|max:100',
            'sort_order' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Upload new image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($about->image_url) {
                Storage::disk('public')->delete($about->image_url);
            }
            $data['image_url'] = $request->file('image')->store('cms/about', 'public');
        }

        // Handle extra_data as JSON
        if ($request->has('extra_data') && is_array($request->extra_data)) {
            $data['extra_data'] = $request->extra_data;
        }

        $about->update($data);

        return redirect()->route('admin.cms.about.index')
            ->with('success', 'Section À propos mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CmsAboutSection $about)
    {
        // Delete associated image
        if ($about->image_url) {
            Storage::disk('public')->delete($about->image_url);
        }

        $about->delete();

        return redirect()->route('admin.cms.about.index')
            ->with('success', 'Section À propos supprimée avec succès.');
    }
}
