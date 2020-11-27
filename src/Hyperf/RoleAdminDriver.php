<?php

declare (strict_types = 1);

namespace Wuxian\Rbac;

use Wuxian\Rbac\Hyperf\Model\RoleAdminModel;

class RoleAdminDriver
{
    /**
     * 获取某个用户的角色id
     * @param int $admin_id 用户id
     * @return array
     */
    public static function rolesIdByUserid(int $admin_id) : array
    {
        return RoleAdminModel::query()->where('id', $admin_id)->pluck('role_id')->toArray();
        
    }

    //通过某角色id获取相应用户id
    public static function adminIdsByRoleId(int $role_id) : array
    {
        return RoleAdminModel::query()->where('role_id', $role_id)->get()->toArray();
    }

    /**
     * 获取某个用户的角色id
     * @param int $admin_id 用户id
     * @return array
     */
    public static function roleIdByUserid(int $admin_id) : array
    {
        return RoleAdminModel::query()->where('admin_id', $admin_id)->pluck('role_id')->toArray();
    }

    
}