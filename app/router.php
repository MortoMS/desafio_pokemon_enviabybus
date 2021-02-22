<?php

use App\Response;
use App\Router;

Router::request(
    ["GET"], 
    BASE, 
    [App\Controllers\Home::class => "home"]
);

Router::request(
    ["GET", "POST"], 
    BASE . "search", 
    [App\Controllers\Search::class => "search"]
);

Router::request(
    ["POST"], 
    BASE . "autocomplete", 
    [App\Controllers\Search::class => "autocomplete"]
);

Router::erro404(function(Response $response){
    return $response->abort(404, []);
});