<?php

namespace App\Controllers;

use App\Response;

class Home
{
    /**
     * @Router("/")
     */
    function home(Response $response)
    {
        $response->view("home")->run();
    }
}