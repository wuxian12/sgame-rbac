<?php

declare (strict_types = 1);

namespace Wuxian\Rbac\Laravel;

use Wuxian\Rbac\Laravel\Model\RoleAdminModel;

class RoleAdminDriver
{

    //通过某角色id获取相应用户id
    public static function adminIdsByRoleId($role_id)
    {
        return RoleAdminModel::query()->where('role_id', $role_id)->pluck('admin_id')->toArray();
    }

    /**
     * 获取某个用户的角色id
     * @param int $admin_id 用户id
     * @return array
     */
    public static function roleIdByUserid($admin_id)
    {
        return RoleAdminModel::query()->where('admin_id', $admin_id)->pluck('role_id')->toArray();
    }

    /**
     * 添加用户角色
     * @param array $data
     * @return array
     */
    public static function addRoleAdmin($data)
    {
        return RoleAdminModel::query()->insert($data);
    }


    //删除
    public static function delRoleAdmin($key,$whereIn)
    {
        return RoleAdminModel::query()->whereIn($key,$whereIn)->delete();
    
    }

    
}