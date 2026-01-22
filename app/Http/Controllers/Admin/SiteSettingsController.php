<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class SiteSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_settings,admin')->only(['index']);
        $this->middleware('permission:edit_settings,admin')->only(['update']);
    }

    public function index()
    {
        $settings = SiteSetting::all()->keyBy('key');
        return view('admin.site-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'top_bar_text' => 'nullable|string|max:255',
            'top_bar_bg_color' => 'nullable|string|max:7',
            'top_bar_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hero_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
            'background_audio_enabled' => 'nullable|boolean',
            'background_audio_volume' => 'nullable|integer|min:0|max:100',
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_whatsapp' => 'nullable|url|max:255',
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_password' => 'nullable|string|max:255',
            'maintenance_title' => 'nullable|string|max:255',
            'maintenance_message' => 'nullable|string',
            'maintenance_end_date' => 'nullable|date',
            'maintenance_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cursor_normal' => 'nullable|image|mimes:png,ico,cur,webp|max:1024',
            'cursor_hover' => 'nullable|image|mimes:png,ico,cur,webp|max:1024',
        ]);

        if ($request->filled('top_bar_text')) {
            SiteSetting::set('top_bar_text', $request->top_bar_text, 'text', 'top_bar');
        }

        if ($request->filled('top_bar_bg_color')) {
            SiteSetting::set('top_bar_bg_color', $request->top_bar_bg_color, 'color', 'top_bar');
        }

        if ($request->hasFile('top_bar_bg_image')) {
            $oldImage = SiteSetting::where('key', 'top_bar_bg_image')->first();
            if ($oldImage && $oldImage->value) {
                Storage::disk('public')->delete($oldImage->value);
            }
            
            $path = $request->file('top_bar_bg_image')->store('settings', 'public');
            SiteSetting::set('top_bar_bg_image', $path, 'image', 'top_bar');
        }

        if ($request->hasFile('hero_bg_image')) {
            $oldImage = SiteSetting::where('key', 'hero_bg_image')->first();
            if ($oldImage && $oldImage->value && $oldImage->value !== 'images/bg_home_page.jpg') {
                Storage::disk('public')->delete($oldImage->value);
            }
            
            $path = $request->file('hero_bg_image')->store('settings', 'public');
            SiteSetting::set('hero_bg_image', $path, 'image', 'hero');
        }

        // Gestion de l'audio d'arrière-plan
        if ($request->hasFile('background_audio_file')) {
            // dd('test');
            $oldAudio = SiteSetting::where('key', 'background_audio')->first();
            if ($oldAudio && $oldAudio->value) {
                Storage::disk('public')->delete($oldAudio->value);
            }
            
            $path = $request->file('background_audio_file')->store('audio', 'public');
            SiteSetting::set('background_audio', $path, 'audio', 'audio');
        }

        // Sauvegarder l'état d'activation de l'audio (seulement si présent dans la requête)
        if ($request->has('background_audio_enabled') || $request->hasAny(['background_audio_file', 'background_audio_volume'])) {
            SiteSetting::set('background_audio_enabled', $request->has('background_audio_enabled') ? '1' : '0', 'boolean', 'audio');
        }

        // Sauvegarder le volume
        if ($request->filled('background_audio_volume')) {
            SiteSetting::set('background_audio_volume', $request->background_audio_volume, 'number', 'audio');
        }

        // Sauvegarder les réseaux sociaux (seulement si au moins un réseau social est présent)
        $socialNetworks = ['facebook', 'instagram', 'twitter', 'youtube', 'tiktok', 'linkedin', 'whatsapp'];
        $hasSocialFields = false;
        foreach ($socialNetworks as $network) {
            if ($request->has('social_' . $network)) {
                $hasSocialFields = true;
                break;
            }
        }
        
        if ($hasSocialFields) {
            foreach ($socialNetworks as $network) {
                $key = 'social_' . $network;
                if ($request->has($key)) {
                    SiteSetting::set($key, $request->input($key), 'url', 'social');
                }
            }
        }

        // Sauvegarder le mode maintenance (seulement si présent dans la requête)
        if ($request->has('maintenance_mode') || $request->hasAny(['maintenance_password', 'maintenance_title', 'maintenance_message', 'maintenance_end_date', 'maintenance_bg_image'])) {
            SiteSetting::set('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0', 'boolean', 'maintenance');
        }
        
        if ($request->filled('maintenance_password')) {
            SiteSetting::set('maintenance_password', $request->maintenance_password, 'text', 'maintenance');
        }
        
        if ($request->filled('maintenance_title')) {
            SiteSetting::set('maintenance_title', $request->maintenance_title, 'text', 'maintenance');
        }
        
        if ($request->filled('maintenance_message')) {
            SiteSetting::set('maintenance_message', $request->maintenance_message, 'text', 'maintenance');
        }

        // Sauvegarder la date de fin de maintenance (seulement si présente dans la requête)
        if ($request->has('maintenance_end_date')) {
            SiteSetting::set('maintenance_end_date', $request->maintenance_end_date, 'datetime', 'maintenance');
        }

        // Gestion de l'image de fond de maintenance
        if ($request->hasFile('maintenance_bg_image')) {
            $oldImage = SiteSetting::where('key', 'maintenance_bg_image')->first();
            if ($oldImage && $oldImage->value) {
                Storage::disk('public')->delete($oldImage->value);
            }
            
            $path = $request->file('maintenance_bg_image')->store('maintenance', 'public');
            SiteSetting::set('maintenance_bg_image', $path, 'image', 'maintenance');
        }

        // Gestion du curseur normal
        if ($request->hasFile('cursor_normal')) {
            $oldCursor = SiteSetting::where('key', 'cursor_normal')->first();
            if ($oldCursor && $oldCursor->value) {
                Storage::disk('public')->delete($oldCursor->value);
            }
            
            $path = $request->file('cursor_normal')->store('cursors', 'public');
            SiteSetting::set('cursor_normal', $path, 'image', 'appearance');
        }

        // Gestion du curseur hover
        if ($request->hasFile('cursor_hover')) {
            $oldCursor = SiteSetting::where('key', 'cursor_hover')->first();
            if ($oldCursor && $oldCursor->value) {
                Storage::disk('public')->delete($oldCursor->value);
            }
            
            $path = $request->file('cursor_hover')->store('cursors', 'public');
            SiteSetting::set('cursor_hover', $path, 'image', 'appearance');
        }

        return redirect()->route('admin.settings.index')->with('success', 'Paramètres mis à jour avec succès');
    }
}
