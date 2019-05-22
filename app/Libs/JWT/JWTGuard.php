<?php

namespace App\Libs\JWT;

use Tymon\JWTAuth\JWTGuard as TymonJWTGuard;

class JWTGuard extends TymonJWTGuard
{

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        if ($this->jwt->getToken() && $this->jwt->check()) {
            $payload = $this->jwt->payload()->get();

            $user = $this->provider->retrieveById($payload['sub']);

            if ($user instanceof JWTAuthenticableContract && $user->checkJWTPayload($payload)) {
                if (!$user->isActive()) {
                    return response()->errorUnauthorized('账号已被冻结，请与管理员联系');
                }

                $this->user = $user;
            }
        }

        return $this->user;
    }

}
