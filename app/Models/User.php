<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Libs\JWT\JWTAuthenticableContract;

class User extends Model implements JWTSubject, Authenticatable, JWTAuthenticableContract
{
    use SoftDeletes {
        restore as softDeleteRestore;
    }

    use EntrustUserTrait {
        EntrustUserTrait::can as may;
        restore as private entrustRestore;
    }

    public function restore()
    {
        $this->softDeleteRestore();
        $this->entrustRestore();
    }

    /**
     * @var array $guarded
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            $this->getJWTType() => [
                'id'           => $this->id,
                'type'         => $this->getJWTType(),
                'sign_version' => $this->sign_version,
            ],
        ];
    }

    /**
     * check payload of jwt.
     *
     * @param array $payload
     * @return bool
     */
    public function checkJWTPayload(array $payload)
    {
        $user = collect(array_get($payload, $this->getJWTType()));

        if (!$user) return false;

        return array_get($user, 'type') === $this->getJWTType()
            && array_get($user, 'sign_version') === $this->sign_version;
    }

    /**
     * Get payload type of jwt.
     *
     * @return string
     */
    public function getJWTType()
    {
        return 'user';
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
    }

    /**
     * @desc hide some columns
     * @param array $columns
     * @return $this
     */
    public function hidden(array $columns)
    {
        return $this->makeHidden($columns);
    }

    /**
     * check status is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return (int)$this->active === 1;
    }

    /**
     * @desc 获取roles
     * @return \Illuminate\Support\Collection
     */
    public function getRoles()
    {
        return $this->roles()->get(['id', 'name'])->map(function ($item) {
            return [
                'id'   => $item->id,
                'name' => $item->name
            ];
        });
    }

    /**
     * @desc 管理员/普通用户 1/0
     */
    const USER_ADMIN = 1;
    const USER_NORMAL = 0;

    /**
     * @desc 获取权限数组
     * @return array
     */
    public function getPermissions()
    {
        $roles       = $this->roles()->get();
        $permissions = [];

        $roles->map(function ($item) use (&$permissions) {
            $permissions = array_merge($item->permissions()->pluck('id')->toArray(), $permissions);
        });
        return array_values(array_unique($permissions));
    }
}
