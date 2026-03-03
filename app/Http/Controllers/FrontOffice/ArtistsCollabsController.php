<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewArtistCollabNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class ArtistsCollabsController extends Controller
{
    public function index()
    {
        return view('front-office.artists-collabs.index');
    }

    public function send(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'social_handle' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:5000',
        ]);

        // 1. Récupérer TOUS les Super Admins avec emails valides
        $superAdmins = User::role('Super Admin')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();

        // Filtrer les admins avec des emails valides
        $validAdmins = $superAdmins->filter(function ($admin) {
            return filter_var($admin->email, FILTER_VALIDATE_EMAIL) !== false;
        });

        // Si aucun admin avec email valide trouvé, envoyer à l'adresse de secours si elle existe
        if ($validAdmins->isEmpty()) {
            $fallbackEmail = config('mail.from.address');
            if (filter_var($fallbackEmail, FILTER_VALIDATE_EMAIL)) {
                try {
                    Notification::route('mail', $fallbackEmail)
                        ->notify(new NewArtistCollabNotification($request->all()));
                } catch (\Exception $e) {
                    Log::error('Failed to send artist collab notification to fallback email: ' . $e->getMessage());
                }
            }
        } else {
            // 2. Envoyer la notification aux Super Admins avec emails valides
            foreach ($validAdmins as $admin) {
                try {
                    $admin->notify(new NewArtistCollabNotification($request->all()));
                } catch (\Exception $e) {
                    Log::error('Failed to send artist collab notification to admin ' . $admin->email . ': ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('artists-collabs')
            ->with('success', 'Your collaboration request has been submitted successfully!');
    }
}
