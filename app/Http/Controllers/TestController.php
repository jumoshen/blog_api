<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\User;
use Identicon\Identicon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [], [
            'name'     => '姓名',
            'email'    => '邮箱',
            'password' => '密码'
        ]);

        return response()->success(User::query()->create([
            'name'     => $request->name,
            'avatar'   => (new Identicon())->getImageDataUri($request->name, 256),
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ApiException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|string|max:255',
            'password' => 'required|string|min:8'
        ], [], []);

        $user = User::query()->where('email', $request->input('email'))->firstOrFail();

        if (!Hash::check($request->input('password'), $user->password)) throw new ApiException('邮箱或密码错误');


//        dd($user instanceof Authenticatable);
        $token = auth('api')->login($user);

        return response()->success([
            'type'    => 'Bearer',
            'token'   => $token,
            'expires' => auth('api')->factory()->getTTL() * 60,
            'user'    => $this->user(),
        ]);
    }
}
