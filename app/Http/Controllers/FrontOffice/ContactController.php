<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyInfo;
use App\Notifications\NewContactNotification;
use Illuminate\Support\Facades\Log;
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

        // Récupérer TOUS les Super Admins avec emails valides
        $superAdmins = User::role('Super Admin')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();

        // Filtrer les admins avec des emails valides
        $validAdmins = $superAdmins->filter(function ($admin) {
            return filter_var($admin->email, FILTER_VALIDATE_EMAIL) !== false;
        });

        // Envoyer la notification
        if ($validAdmins->isEmpty()) {
            $fallbackEmail = config('mail.from.address');
            if (filter_var($fallbackEmail, FILTER_VALIDATE_EMAIL)) {
                try {
                    Notification::route('mail', $fallbackEmail)
                        ->notify(new NewContactNotification($request->all()));
                } catch (\Exception $e) {
                    Log::error('Failed to send contact notification to fallback email: ' . $e->getMessage());
                }
            }
        } else {
            // Envoyer aux Super Admins avec emails valides
            foreach ($validAdmins as $admin) {
                try {
                    $admin->notify(new NewContactNotification($request->all()));
                } catch (\Exception $e) {
                    Log::error('Failed to send contact notification to admin ' . $admin->email . ': ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully! We will get back to you soon.');
    }
}
