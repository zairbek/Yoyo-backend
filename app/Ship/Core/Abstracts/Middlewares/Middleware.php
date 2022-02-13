<?php

namespace App\Ship\Core\Abstracts\Middlewares;

use Closure;
use Illuminate\Http\Request;

abstract class Middleware
{
    abstract public function handle(Request $request, Closure $next, ...$guards): mixed;
}
