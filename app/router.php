<?php

use App\Response;
use App\Router;
use App\System;

/**
 * URL padrao da aplicacao
 *
 * @var string
 */
$base = System::getEnv('APP_URL');

Router::request(
    ["GET"],
    $base,
    [App\Controllers\Home::class => "home"]
);

Router::request(
    ["GET", "POST"],
    $base . "search",
    [App\Controllers\Search::class => "search"]
);

Router::request(
    ["POST"],
    $base . "autocomplete",
    [App\Controllers\Search::class => "autocomplete"]
);

Router::erro404(function (Response $response) {
    return $response->abort(404, []);
});
