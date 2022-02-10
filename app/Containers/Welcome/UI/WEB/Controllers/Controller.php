<?php

namespace App\Containers\Welcome\UI\WEB\Controllers;

use App\Ship\Core\Abstracts\Controllers\WebController;

class Controller extends WebController
{
    public function __invoke()
    {
        return view('welcome');
    }
}
