<?php
namespace Wuxian\Rbac;

use App\Model\RolePermission;

class RolePermission
{
    /**
     * 获取角色的权限id
     * @param array $role_id 角色id
     * @return array
     */
    public static function permissionIdByRoleids(array $role_ids) : array
    {
        return RolePermission::query()->whereIn('role_id', $role_ids)->pluck('permission_id')->toArray();
    }


    
