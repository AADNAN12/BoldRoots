<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function index(Attribute $attribute)
    {
        $values = $attribute->values()->orderBy('id', 'desc')->get();
        return view('admin.attribute-values.index', compact('attribute', 'values'));
    }

    public function store(Request $request, Attribute $attribute)
    {
        $rules = [
            'value' => 'required|string|max:255',
        ];

        if ($attribute->type === 'color') {
            $rules['color_code'] = 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/';
        }

        $validated = $request->validate($rules);
        $validated['attribute_id'] = $attribute->id;

        AttributeValue::create($validated);

        return redirect()->route('admin.attribute-values.index', $attribute)
            ->with('success', 'Valeur ajoutée avec succès.');
    }

    public function update(Request $request, Attribute $attribute, AttributeValue $attributeValue)
    {
        $rules = [
            'value' => 'required|string|max:255',
        ];

        if ($attribute->type === 'color') {
            $rules['color_code'] = 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/';
        }

        $validated = $request->validate($rules);

        $attributeValue->update($validated);

        return redirect()->route('admin.attribute-values.index', $attribute)
            ->with('success', 'Valeur modifiée avec succès.');
    }

    public function destroy(Attribute $attribute, AttributeValue $attributeValue)
    {
        try {
            $attributeValue->delete();
            return redirect()->route('admin.attribute-values.index', $attribute)
                ->with('success', 'Valeur supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.attribute-values.index', $attribute)
                ->with('error', 'Erreur lors de la suppression de la valeur.');
        }
    }
}
