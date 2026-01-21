<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|max:255'
            ]);

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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email invalide. Veuillez vérifier votre adresse email.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Newsletter subscription error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ], 500);
        }
    }
}
