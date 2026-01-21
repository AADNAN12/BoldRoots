<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\CompanyInfo;


class MaintenanceController extends Controller
{
    public function index()
    {
        $title = SiteSetting::get('maintenance_title', 'STRUGGLE | ENDURE | WIN !');
        $message = SiteSetting::get('maintenance_message', 'Notre site est actuellement en maintenance.');
        $bgImage = SiteSetting::get('maintenance_bg_image');
        $endDate = SiteSetting::get('maintenance_end_date');
        $whatsappUrl = SiteSetting::get('social_whatsapp');
        $companyInfo = CompanyInfo::first();
        
        return view('front-office.maintenance.index', compact('title', 'message', 'bgImage', 'endDate', 'whatsappUrl', 'companyInfo'));
    }
    
    public function verify(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);
        
        $correctPassword = SiteSetting::get('maintenance_password', 'boldroots2024');
        
        if ($request->password === $correctPassword) {
            session(['maintenance_access' => true]);
            return redirect('/')->with('success', 'Accès autorisé !');
        }
        
        return back()->with('error', 'Mot de passe incorrect.');
    }
}
