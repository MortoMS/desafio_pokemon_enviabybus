<?php

namespace App\Controllers;

use App\Response;

class Home
{
    /**
     * @Router("/")
     *
     * @return void
     */
    public function home(Response $response)
    {
        $response->view("home")->run();
    }
}
