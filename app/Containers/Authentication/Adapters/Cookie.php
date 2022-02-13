<?php

namespace App\Containers\Authentication\Adapters;

use Illuminate\Support\Facades\Cookie as CookieFacade;
use Symfony\Component\HttpFoundation\Cookie as HttpCookie;

class Cookie
{
    public const REFRESH_TOKEN_COOKIE_NAME = 'refresh-token';

    /**
     * Refresh-token токен положим в куки и ограничим с доменом и httponly
     *
     * @param string $refreshToken
     * @return HttpCookie
     */
    public static function make(string $refreshToken): HttpCookie
    {
        return HttpCookie::create(
            self::REFRESH_TOKEN_COOKIE_NAME,
            $refreshToken,
            now()->addSeconds(config('passport.tokens.refresh_token_lifetime', 86400)),
            null,
            config('passport.tokens.refresh_token_cache_domain'),
            true
        );
    }

    /**
     * @return HttpCookie
     */
    public static function forget(): HttpCookie
    {
        return CookieFacade::forget(self::REFRESH_TOKEN_COOKIE_NAME);
    }
}
