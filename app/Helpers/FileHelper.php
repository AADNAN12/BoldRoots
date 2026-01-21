<?php

if (!function_exists('getFileIcon')) {
    /**
     * Retourne l'icône appropriée selon le type MIME du fichier
     */
    function getFileIcon($mimeType) {
        $iconMap = [
            // Images
            'image/jpeg' => 'mdi mdi-file-image',
            'image/jpg' => 'mdi mdi-file-image',
            'image/png' => 'mdi mdi-file-image',
            'image/gif' => 'mdi mdi-file-image',
            'image/webp' => 'mdi mdi-file-image',
            'image/svg+xml' => 'mdi mdi-file-image',
            
            // Documents PDF
            'application/pdf' => 'mdi mdi-file-pdf-box',
            
            // Documents Word
            'application/msword' => 'mdi mdi-file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'mdi mdi-file-word',
            
            // Documents Excel
            'application/vnd.ms-excel' => 'mdi mdi-file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'mdi mdi-file-excel',
            
            // Documents PowerPoint
            'application/vnd.ms-powerpoint' => 'mdi mdi-file-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'mdi mdi-file-powerpoint',
            
            // Archives
            'application/zip' => 'mdi mdi-folder-zip',
            'application/x-rar-compressed' => 'mdi mdi-folder-zip',
            'application/x-7z-compressed' => 'mdi mdi-folder-zip',
            
            // Texte
            'text/plain' => 'mdi mdi-file-document',
            'text/csv' => 'mdi mdi-file-delimited',
            
            // Vidéos
            'video/mp4' => 'mdi mdi-file-video',
            'video/avi' => 'mdi mdi-file-video',
            'video/mov' => 'mdi mdi-file-video',
            'video/wmv' => 'mdi mdi-file-video',
            
            // Audio
            'audio/mp3' => 'mdi mdi-file-music',
            'audio/wav' => 'mdi mdi-file-music',
            'audio/ogg' => 'mdi mdi-file-music',
        ];
        
        return $iconMap[$mimeType] ?? 'mdi mdi-file';
    }
}

if (!function_exists('formatFileSize')) {
    /**
     * Formate la taille d'un fichier en octets vers une unité lisible
     */
    function formatFileSize($bytes, $precision = 2) {
        if ($bytes == 0) {
            return '0 B';
        }
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $base = log($bytes, 1024);
        $index = floor($base);
        
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $units[$index];
    }
}

if (!function_exists('getFileTypeFromMime')) {
    /**
     * Détermine le type de fichier à partir du type MIME
     */
    function getFileTypeFromMime($mimeType) {
        if (strpos($mimeType, 'image/') === 0) {
            return 'image';
        } elseif (strpos($mimeType, 'video/') === 0) {
            return 'video';
        } elseif (strpos($mimeType, 'audio/') === 0) {
            return 'audio';
        } elseif (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ])) {
            return 'document';
        } else {
            return 'other';
        }
    }
}
