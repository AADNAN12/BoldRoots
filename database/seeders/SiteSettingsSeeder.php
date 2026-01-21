<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'top_bar_text',
                'value' => 'DEVOTE YOURSELF TO THE BOLD ROOTS',
                'type' => 'text',
                'group' => 'top_bar'
            ],
            [
                'key' => 'top_bar_bg_color',
                'value' => '#000000',
                'type' => 'color',
                'group' => 'top_bar'
            ],
            [
                'key' => 'top_bar_bg_image',
                'value' => null,
                'type' => 'image',
                'group' => 'top_bar'
            ],
            [
                'key' => 'hero_bg_image',
                'value' => 'images/bg_home_page.jpg',
                'type' => 'image',
                'group' => 'hero'
            ],
            [
                'key' => 'background_audio',
                'value' => null,
                'type' => 'audio',
                'group' => 'audio'
            ],
            [
                'key' => 'background_audio_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'audio'
            ],
            [
                'key' => 'background_audio_volume',
                'value' => '50',
                'type' => 'number',
                'group' => 'audio'
            ],
            [
                'key' => 'social_facebook',
                'value' => null,
                'type' => 'url',
                'group' => 'social'
            ],
            [
                'key' => 'social_instagram',
                'value' => null,
                'type' => 'url',
                'group' => 'social'
            ],
            [
                'key' => 'social_twitter',
                'value' => null,
                'type' => 'url',
                'group' => 'social'
            ],
            [
                'key' => 'social_youtube',
                'value' => null,
                'type' => 'url',
                'group' => 'social'
            ],
            [
                'key' => 'social_tiktok',
                'value' => null,
                'type' => 'url',
                'group' => 'social'
            ],
            [
                'key' => 'social_linkedin',
                'value' => null,
                'type' => 'url',
                'group' => 'social'
            ],
            [
                'key' => 'social_whatsapp',
                'value' => null,
                'type' => 'url',
                'group' => 'social'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'maintenance'
            ],
            [
                'key' => 'maintenance_password',
                'value' => 'boldroots2024',
                'type' => 'text',
                'group' => 'maintenance'
            ],
            [
                'key' => 'maintenance_title',
                'value' => 'STRUGGLE | ENDURE | WIN !',
                'type' => 'text',
                'group' => 'maintenance'
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'Notre site est actuellement en maintenance. Nous reviendrons bientôt plus forts !',
                'type' => 'text',
                'group' => 'maintenance'
            ],
            [
                'key' => 'maintenance_bg_image',
                'value' => null,
                'type' => 'image',
                'group' => 'maintenance'
            ],
            [
                'key' => 'maintenance_end_date',
                'value' => null,
                'type' => 'datetime',
                'group' => 'maintenance'
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
