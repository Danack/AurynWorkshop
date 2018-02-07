<?php

namespace AurynWorkshop\Middleware;

use Slim\Middleware\Session as SlimSession;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Session extends SlimSession
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        try {
            $this->startSession();

            return $next($request, $response);
        }
        catch (\RuntimeException $re) {
            // TODO - persuade the library maintainer to use a specific extension
            // https://github.com/ezimuel/PHP-Secure-Session/issues/29
            \error_log("The session had ended, or at least the encryption key had elapsed.");

            if (strcasecmp("Authentication failed", $re->getMessage()) === 0) {
                return $response->withStatus(302)->withHeader('Location', '/login');
            }
            throw $re;
        }
    }

    protected function renew()
    {
        $settings = $this->settings;
        $name = 'KEY_'.$settings['name'];

        if (empty($_COOKIE[$name])) {
            return;
        }

        setcookie(
            $name,
            $_COOKIE[$name],
            time() + $settings['lifetime'],
            $settings['path'],
            $settings['domain'],
            $settings['secure'],
            $settings['httponly']
        );
    }


    /**
     * Start session
     */
    protected function startSession()
    {
        $settings = $this->settings;
        $name = $settings['name'];

        session_set_cookie_params(
            $settings['lifetime'],
            $settings['path'],
            $settings['domain'],
            $settings['secure'],
            $settings['httponly']
        );

        $inactive = session_status() === PHP_SESSION_NONE;

        if ($inactive) {
            // Refresh session cookie when "inactive",
            // else PHP won't know we want this to refresh
            if ($settings['autorefresh'] && isset($_COOKIE[$name])) {
                setcookie(
                    $name,
                    $_COOKIE[$name],
                    time() + $settings['lifetime'],
                    $settings['path'],
                    $settings['domain'],
                    $settings['secure'],
                    $settings['httponly']
                );
                $this->renew();
            }
        }

        session_name($name);
        session_cache_limiter(false);
        if ($inactive) {
            session_start();
        }
    }
}
