<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 当前登录用户
     *
     * @return User|null
     * @throws ApiException
     */
    public function user()
    {
        if (!($user = auth('api')->user())) {
            throw new ApiException('当前用户未登陆。', 401);
        }

        return $user->hidden(['created_at', 'updated_at', 'is_admin', 'sin_version', 'email_verified_at', 'password', 'remember_token']);
    }
}
