<?php
spl_autoload_register(function($class){
    $paths = [
        __DIR__ . '/../app/Models/' . $class . '.php',
        __DIR__ . '/../app/Controllers' . $class . '.php',
        __DIR__ . '/../app/Core' . $class . '.php'
    ];

    foreach ($paths as $file){
        if(file_exists($file)){
            require_once $file;
            return;
        }
    }
});


?>