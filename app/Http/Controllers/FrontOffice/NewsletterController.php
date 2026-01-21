<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        try {
            $newsletter = Newsletter::firstOrCreate(
                ['email' => $request->email],
                ['is_active' => true]
            );

            if ($newsletter->wasRecentlyCreated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Merci ! Vous êtes maintenant inscrit à notre newsletter.'
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Vous êtes déjà inscrit à notre newsletter.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ], 500);
        }
    }
}
