<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;

class ContactController extends Controller
{
    public function index()
    {
        $companyInfo = CompanyInfo::first();
        return view('front-office.contact.index', compact('companyInfo'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Ici vous pouvez ajouter la logique pour envoyer l'email
        // Par exemple: Mail::to('contact@boldroots.com')->send(new ContactMail($request->all()));

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully! We will get back to you soon.');
    }
}
