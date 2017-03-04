<?php

define("ROOT_URL", "http://localhost/rome");
define("BASE_PATH", "http://localhost/rome/app");
define("MODULES_FOLDER", "app/modules");
define("CODE_FOLDER", "app/code");
// Autoloading classes
spl_autoload_register(function ($class_name) {
    // This finds all files and subdirectories and alters through them to find the class used
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.'));
    foreach ($it as $file){
        if(strpos(strtolower($file), strtolower($class_name))!==false){
            $file_parts = pathinfo($file);
            if($file_parts['extension'] == "php") require_once $file;
        }
    }
});