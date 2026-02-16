<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use App\Models\User;
use App\Notifications\NewContactNotification;
use Illuminate\Support\Facades\Notification;

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

        // Récupérer TOUS les Super Admins
        $superAdmins = User::role('Super Admin')->get();

        // Envoyer la notification
        if ($superAdmins->isEmpty()) {
            Notification::route('mail', config('mail.from.address'))
                ->notify(new NewContactNotification($request->all()));
        } else {
            // Envoyer à TOUS les Super Admins
            foreach ($superAdmins as $admin) {
                $admin->notify(new NewContactNotification($request->all()));
            }
        }

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully! We will get back to you soon.');
    }
}
