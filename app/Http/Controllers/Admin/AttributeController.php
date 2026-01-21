<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::withCount('values')->orderBy('id', 'desc')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:attributes,slug',
            'type' => 'required|in:select,color',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Attribute::create($validated);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribut créé avec succès.');
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:attributes,slug,' . $attribute->id,
            'type' => 'required|in:select,color',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $attribute->update($validated);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribut modifié avec succès.');
    }

    public function destroy(Attribute $attribute)
    {
        try {
            $attribute->delete();
            return redirect()->route('admin.attributes.index')
                ->with('success', 'Attribut supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.attributes.index')
                ->with('error', 'Erreur lors de la suppression de l\'attribut.');
        }
    }
}
