<?php

declare (strict_types = 1);

namespace Wuxian\Rbac;

use App\Model\AuthPermission as Permission;
use App\Model\AuthRole as Role;
use App\Model\AuthEmployee as RoleAdmin; // 待定和账号绑定的角色管理
use App\Model\AuthRolePermission as RolePermission;

class RoleAdmin
{
    /**
     * 获取某个用户的角色id
     * @param int $admin_id 用户id
     * @return array
     */
    public static function rolesIdByUserid(int $admin_id) : array
    {
        return RoleAdmin::query()->where('id', $admin_id)->pluck('role_id')->toArray();
        
    }

    //通过某角色id获取相应用户id
    public static function adminIdsByRoleId(int $role_id) : array
    {
        return RoleAdmin::query()->where('role_id', $role_id)->get()->toArray();
    }

    /**
     * 获取某个用户的角色id
     * @param int $admin_id 用户id
     * @return array
     */
    public static function roleIdByUserid(int $admin_id) : array
    {
        return RoleAdmin::query()->where('admin_id', $admin_id)->pluck('role_id')->toArray();
    }

    
}