<?php

spl_autoload_register(function($class)
{
    $class = str_replace("App\\", "", $class);
	$class = str_replace('\\', "/", $class);
    $file = RAIZ . "/src/" . $class . ".php";

    if (file_exists($file))
    {
        return require_once $file;
    }

    throw new Exception("Ocorreu um erro na tentativa de carregar a class em '". $file ."'.");
});