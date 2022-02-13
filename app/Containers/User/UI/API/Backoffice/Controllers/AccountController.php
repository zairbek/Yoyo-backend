<?php

namespace App\Containers\User\UI\API\Backoffice\Controllers;

use App\Ship\Core\Abstracts\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AccountController extends ApiController
{
    public function get()
    {
        return Response::json(
            Auth::guard('api')->user()
        );
    }
}
