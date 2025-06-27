<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsFaq;
use Illuminate\Http\Request;

class CmsFaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = CmsFaq::orderBy('sort_order')->paginate(10);
        $categories = CmsFaq::distinct('category')->pluck('category')->filter();
        return view('admin.cms.faqs.index', compact('faqs', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CmsFaq::distinct('category')->pluck('category')->filter();
        return view('admin.cms.faqs.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'required|integer|min:0'
        ]);

        $data = $request->all();
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');

        CmsFaq::create($data);

        return redirect()->route('admin.cms.faqs.index')
            ->with('success', 'FAQ créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CmsFaq $faq)
    {
        return view('admin.cms.faqs.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CmsFaq $faq)
    {
        $categories = CmsFaq::distinct('category')->pluck('category')->filter();
        return view('admin.cms.faqs.edit', compact('faq', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CmsFaq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'required|integer|min:0'
        ]);

        $data = $request->all();
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');

        $faq->update($data);

        return redirect()->route('admin.cms.faqs.index')
            ->with('success', 'FAQ mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CmsFaq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.cms.faqs.index')
            ->with('success', 'FAQ supprimée avec succès.');
    }
}
