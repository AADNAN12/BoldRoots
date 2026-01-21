<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyInfoController extends Controller
{
    public function index()
    {
        $companyInfo = CompanyInfo::first();
        
        if (!$companyInfo) {
            $companyInfo = new CompanyInfo();
        }
        
        return view('admin.company-info.index', compact('companyInfo'));
    }

    public function update(Request $request)
    {
        // dd($request->all());    
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|string|max:50',
            'registration_number' => 'nullable|string|max:50',
            'logo' => 'nullable|mimes:jpeg,png,jpg,gif,avif|max:2048',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:50',
            'iban' => 'nullable|string|max:50',
        ]);
        $companyInfo = CompanyInfo::first();

        if (!$companyInfo) {
            $companyInfo = new CompanyInfo();
        }

        // Handle logo upload
        if ($request->has('logo')) {
            // Delete old logo if exists
            if ($companyInfo->logo_path) {
                Storage::disk('public')->delete($companyInfo->logo_path);
            }

            $logoPath = $request->file('logo')->store('company', 'public');
            $validated['logo_path'] = $logoPath;
        }

        if ($companyInfo->exists) {
            $companyInfo->update($validated);
        } else {
            CompanyInfo::create($validated);
        }

        return redirect()->route('admin.company-info.index')
            ->with('success', 'Informations de l\'entreprise mises à jour avec succès.');
    }
}
