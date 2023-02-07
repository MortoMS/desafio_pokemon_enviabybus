<?php

use App\System;
use App\Response;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'defines.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoloading.php';

try {
    session_start();
    date_default_timezone_set("America/Sao_Paulo");
    System::loadingFileEnv();

    require_once System::mountAddress("router.php");
} catch (Exception $e) {
    $res = new Response();

    return $res->abort(500, ["error" => $e->getMessage()]);
}
