<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_faqs,admin')->only(['index', 'show']);
        $this->middleware('permission:create_faqs,admin')->only(['create', 'store']);
        $this->middleware('permission:edit_faqs,admin')->only(['edit', 'update']);
        $this->middleware('permission:delete_faqs,admin')->only(['destroy']);
    }

    public function index()
    {
        $faqs = Faq::orderBy('order', 'asc')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ créée avec succès.');
    }


    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ mise à jour avec succès.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        
        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ supprimée avec succès.');
    }

    public function toggleStatus(Faq $faq)
    {
        $faq->update(['is_active' => !$faq->is_active]);
        
        return redirect()->route('admin.faqs.index')
            ->with('success', 'Statut de la FAQ mis à jour avec succès.');
    }
}
