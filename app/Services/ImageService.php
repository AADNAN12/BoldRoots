<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class ImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Convertit une image uploadée en WebP, la redimensionne si nécessaire,
     * et l'optimise avec Spatie Image Optimizer.
     *
     * @param \Illuminate\Http\UploadedFile $imageFile
     * @param string $directory  Sous-dossier dans storage/app/public (ex: 'products')
     * @param string $prefix     Préfixe du nom de fichier
     * @param int $maxWidth      Largeur maximale (0 = pas de redimensionnement)
     * @param int $quality       Qualité WebP (1-100)
     * @return string            Le chemin relatif dans le disque public
     */
    public function convertAndOptimize(
        $imageFile,
        string $directory = 'products',
        string $prefix = '',
        int $maxWidth = 1200,
        int $quality = 80
    ): string {
        // Générer un nom de fichier unique en .webp
        $filename = $prefix . time() . '_' . uniqid() . '.webp';

        // Lire l'image avec Intervention Image
        $image = $this->manager->read($imageFile->getPathname());

        // Redimensionner si l'image est plus large que maxWidth
        if ($maxWidth > 0 && $image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Encoder en WebP
        $encoded = $image->toWebp($quality);

        // Sauvegarder dans storage/app/public/{directory}
        $storagePath = $directory . '/' . $filename;
        Storage::disk('public')->put($storagePath, (string) $encoded);

        // Optimiser avec Spatie Image Optimizer
        $fullPath = Storage::disk('public')->path($storagePath);
        ImageOptimizer::optimize($fullPath);

        return $storagePath;
    }
}
