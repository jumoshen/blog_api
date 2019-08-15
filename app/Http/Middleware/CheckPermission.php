<?php

namespace App\Http\Middleware;

use App\Models\Auth\Permission;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @param $permissionId
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permissionId)
    {
        $permission = Permission::query()->find($permissionId);

        if (!$permission || !$request->user()->may($permission->name)) {
            return response()->errorForbidden('您没有权限执行当前操作！');
        }

        return $next($request);
    }
}
