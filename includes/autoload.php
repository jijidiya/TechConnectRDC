<?php

// Autoloader : charge automatiquement les classes
// quand on instancie un modèle, un controller ou une classe core
spl_autoload_register(function ($class) {

    // Dossiers où se trouvent nos classes
    $paths = [
        __DIR__ . '/../app/Models/' . $class . '.php',
        __DIR__ . '/../app/Controllers/' . $class . '.php',
        __DIR__ . '/../app/Core/' . $class . '.php'
    ];

    // On teste chaque chemin
    // Dès qu'on trouve le fichier correspondant à la classe,
    // on l'inclut et on arrête la boucle
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // Si aucune classe n'est trouvée :
    // -> soit le nom de la classe est mal écrit
    // -> soit le fichier n'est pas dans le bon dossier
});

?>