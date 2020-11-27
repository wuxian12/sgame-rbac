<?php
namespace Wuxian\Rbac;

use Wuxian\Rbac\Hyperf\Model\RolePermissionModel;

class RolePermissionDriver
{
    /**
     * 获取角色的权限id
     * @param array $role_id 角色id
     * @return array
     */
    public static function permissionIdByRoleids(array $role_ids) : array
    {
        return RolePermissionModel::query()->whereIn('role_id', $role_ids)->pluck('permission_id')->toArray();
    }


    
