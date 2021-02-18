<?php

spl_autoload_register(function($class)
{
    $class = str_replace("App\\", "", $class);
	$class = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    $file = RAIZ . DIRECTORY_SEPARATOR .  "src" . DIRECTORY_SEPARATOR . $class . ".php";

    if (file_exists($file))
    {
        return require_once $file;
    }

    throw new Exception("Ocorreu um erro na tentativa de carregar a class em '". $file ."'.");
});