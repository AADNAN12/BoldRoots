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

        // 1. Récupérer TOUS les Super Admins
        $superAdmins = User::role('Super Admin')->get();

        // Si aucun admin trouvé, envoyer à une adresse de secours
        if ($superAdmins->isEmpty()) {
            Notification::route('mail', config('mail.from.address'))
                ->notify(new NewArtistCollabNotification($request->all()));
        } else {
            // 2. Envoyer la notification à TOUS les Super Admins
            foreach ($superAdmins as $admin) {
                $admin->notify(new NewArtistCollabNotification($request->all()));
            }
        }

        return redirect()->route('artists-collabs')
            ->with('success', 'Your collaboration request has been submitted successfully!');
    }
}
