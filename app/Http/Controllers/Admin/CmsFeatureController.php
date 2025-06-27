<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsFeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features = CmsFeature::orderBy('sort_order')->paginate(10);
        return view('admin.cms.features.index', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cms.features.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Upload image if provided
        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('cms/features', 'public');
        }

        CmsFeature::create($data);

        return redirect()->route('admin.cms.features.index')
            ->with('success', 'Fonctionnalité créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CmsFeature $feature)
    {
        return view('admin.cms.features.show', compact('feature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CmsFeature $feature)
    {
        return view('admin.cms.features.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CmsFeature $feature)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
            'link_url' => 'nullable|url',
            'link_text' => 'nullable|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Upload new image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($feature->image_url) {
                Storage::disk('public')->delete($feature->image_url);
            }
            $data['image_url'] = $request->file('image')->store('cms/features', 'public');
        }

        $feature->update($data);

        return redirect()->route('admin.cms.features.index')
            ->with('success', 'Fonctionnalité mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CmsFeature $feature)
    {
        // Delete associated image
        if ($feature->image_url) {
            Storage::disk('public')->delete($feature->image_url);
        }

        $feature->delete();

        return redirect()->route('admin.cms.features.index')
            ->with('success', 'Fonctionnalité supprimée avec succès.');
    }
}
