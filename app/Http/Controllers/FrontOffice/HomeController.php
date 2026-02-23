<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Affichage de la page d'accueil BOLDROOTS
     */
    public function index()
    {
        $settings = SiteSetting::all()->keyBy('key');
        $featuredProducts = Product::where('is_featured', true)->take(4)->get();
        
        // Récupérer les paramètres audio
        $audioEnabled = SiteSetting::get('background_audio_enabled', '0');
        $audioPath = SiteSetting::get('background_audio');
        $audioVolume = SiteSetting::get('background_audio_volume', '50');
        
        // Déterminer quelle page home afficher
        $homePageType = SiteSetting::get('home_page_type', 'default');
        
        switch ($homePageType) {
            case 'alternative':
                $view = 'front-office.home.alternative';
                break;
            case 'premium':
                $view = 'front-office.home.premium';
                break;
            default:
                $view = 'front-office.home.index';
                break;
        }
        
        return view($view, compact('settings', 'featuredProducts', 'audioEnabled', 'audioPath', 'audioVolume'));
    }

    // public function about()
    // {
    //     return view('home.about');
    // }
    // public function terms_conditions()
    // {
    //     return view('home.terms_conditions');
    // }
    // public function privacy_policy()
    // {
    //     return view('home.privacy_policy');
    // }
}
