<?php

namespace App\Libs\JWT;

interface JWTAuthenticableContract
{
    /**
     * Get payload type of jwt.
     *
     * @return string
     */
    public function getJWTType();

    /**
     * check payload of jwt.
     *
     * @param array $payload
     * @return bool
     */
    public function checkJWTPayload(array $payload);

    /**
     * check status is active.
     *
     * @return bool
     */
    public function isActive();
}
