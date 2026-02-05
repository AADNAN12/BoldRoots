<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewArtistCollabNotification;
use Illuminate\Support\Facades\Notification;

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

        // 1. Récupérer le Super Admin
        // Attention: Assurez-vous que le package 'spatie/laravel-permission' est installé
        // Sinon, faites : User::where('email', 'votre@email.com')->first();
        $superAdmin = User::role('Super Admin')->first();

        // Si aucun admin trouvé, on peut envoyer à une adresse en dur (fallback)
        if (!$superAdmin) {
            // Optionnel : Logique de secours ou envoi via Notification::route
             Notification::route('mail', config('mail.from.address'))
                ->notify(new NewArtistCollabNotification($request->all()));
        } else {
            // 2. Envoyer la notification à l'admin
            $superAdmin->notify(new NewArtistCollabNotification($request->all()));
        }

        return redirect()->route('artists-collabs')
            ->with('success', 'Your collaboration request has been submitted successfully!');
    }
}
