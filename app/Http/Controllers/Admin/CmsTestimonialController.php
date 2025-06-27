<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CmsTestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = CmsTestimonial::orderBy('sort_order')->paginate(10);
        return view('admin.cms.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cms.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'sort_order' => 'required|integer|min:0',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');

        // Upload avatar if provided
        if ($request->hasFile('avatar')) {
            $data['avatar_url'] = $request->file('avatar')->store('cms/testimonials', 'public');
        }

        CmsTestimonial::create($data);

        return redirect()->route('admin.cms.testimonials.index')
            ->with('success', 'Témoignage créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CmsTestimonial $testimonial)
    {
        return view('admin.cms.testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CmsTestimonial $testimonial)
    {
        return view('admin.cms.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CmsTestimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'sort_order' => 'required|integer|min:0',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');

        // Upload new avatar if provided
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($testimonial->avatar_url) {
                Storage::disk('public')->delete($testimonial->avatar_url);
            }
            $data['avatar_url'] = $request->file('avatar')->store('cms/testimonials', 'public');
        }

        $testimonial->update($data);

        return redirect()->route('admin.cms.testimonials.index')
            ->with('success', 'Témoignage mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CmsTestimonial $testimonial)
    {
        // Delete associated avatar
        if ($testimonial->avatar_url) {
            Storage::disk('public')->delete($testimonial->avatar_url);
        }

        $testimonial->delete();

        return redirect()->route('admin.cms.testimonials.index')
            ->with('success', 'Témoignage supprimé avec succès.');
    }
}
