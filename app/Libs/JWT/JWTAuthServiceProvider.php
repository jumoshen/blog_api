<?php

namespace App\Libs\JWT;

use Tymon\JWTAuth\Http\Parser\AuthHeaders;
use Tymon\JWTAuth\Http\Parser\Parser;
use Tymon\JWTAuth\Http\Parser\QueryString;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class JWTAuthServiceProvider extends LaravelServiceProvider
{

    /**
     * Extend Laravel's Auth.
     *
     * @return void
     */
    protected function extendAuthGuard()
    {
        $this->app['auth']->extend('jwt', function ($app, $name, array $config) {
            $guard = new JWTGuard(
                $app['tymon.jwt'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }

    /**
     * 只接受Authorization头和token参数获取token
     */
    protected function registerTokenParser()
    {
        $this->app->singleton('tymon.jwt.parser', function ($app) {
            $authHeaderParser  = new AuthHeaders();
            $queryStringParser = new QueryString();
            $queryStringParser->setKey('token');
            $parser = new Parser(
                $app['request'],
                [$authHeaderParser, $queryStringParser]
            );

            $app->refresh('request', $parser, 'setRequest');

            return $parser;
        });
    }
}
