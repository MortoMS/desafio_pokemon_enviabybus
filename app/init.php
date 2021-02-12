<?php

use App\System;
use App\Response;

define("RAIZ", __DIR__);

try 
{
    session_start();
    
    require_once RAIZ . "/autoloading.php";
    
    date_default_timezone_set("America/Sao_Paulo");

    System::loadingFileEnv();

    define("BASE", getenv("APP_URL"));

    require_once RAIZ . "/router.php";
}
catch(Exception $e)
{
    $res = new Response();
    return $res->abort(500, ["error" => $e->getMessage()]);
}
