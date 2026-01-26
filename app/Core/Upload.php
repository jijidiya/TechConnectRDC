<?php

/**
 * Upload
 *
 * Gère l’upload sécurisé des fichiers :
 *  - vérification type MIME
 *  - taille max
 *  - extension autorisée
 *  - nom unique
 *
 * Utilisé pour :
 *  - images produits
 *  - logos fournisseurs
 */

class Upload
{
    /**
     * Extensions autorisées
     */
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Taille maximale (2 Mo)
     */
    private int $maxSize = 2_000_000;

    /**
     * Upload un fichier image
     *
     * @param array  $file   $_FILES['image']
     * @param string $path   chemin de destination
     *
     * @return string|null   nom du fichier ou null si échec
     */
    public function image(array $file, string $path): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if ($file['size'] > $this->maxSize) {
            return null;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $this->allowedExtensions)) {
            return null;
        }

        // Vérification MIME réelle
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!str_starts_with($mime, 'image/')) {
            return null;
        }

        // Nom unique
        $filename = uniqid('img_', true) . '.' . $extension;

        // Création du dossier si absent
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $destination = rtrim($path, '/') . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return $filename;
    }
}
