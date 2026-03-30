<?php

if (!function_exists('image_url')) {
    /**
     * Retourne l'URL d'une image ou d'un document (base64 ou fichier)
     * 
     * @param mixed $model Le modèle contenant l'image/document
     * @param string $imageField Le nom du champ contenant le chemin du fichier
     * @param string $dataField Le nom du champ contenant les données base64
     * @return string L'URL ou les données base64
     */
    function image_url($model, string $imageField = 'image', string $dataField = 'image_data'): string
    {
        if (!$model) {
            return '';
        }
        
        // Priorité 1: données base64 en DB
        if (!empty($model->{$dataField})) {
            return $model->{$dataField};
        }
        
        // Priorité 2: fichier (pour compatibilité avec anciennes données)
        if (!empty($model->{$imageField})) {
            // Si c'est déjà une data URI
            if (str_starts_with($model->{$imageField}, 'data:')) {
                return $model->{$imageField};
            }
            // Sinon, c'est un chemin de fichier
            return asset('storage/' . $model->{$imageField});
        }
        
        return '';
    }
}
