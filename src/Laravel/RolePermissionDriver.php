<?php
namespace Wuxian\Rbac\Laravel;

use Wuxian\Rbac\Laravel\Model\RolePermissionModel;

class RolePermissionDriver
{
    /**
     * 获取角色的权限id
     * @param array $role_id 角色id
     * @return array
     */
    public static function permissionIdByRoleids($role_ids)
    {
        return RolePermissionModel::query()->whereIn('role_id', $role_ids)->pluck('permission_id')->toArray();
    }

    /**
     * 添加用户角色
     * @param array $data
     * @return array
     */
    public static function addRolepermission($role_id, $permissionIds)
    {
    	//删除之前的
    	static::delRolepermission('role_id',[$role_id]);
    	$permissionIdsArr = explode(',', $permissionIds);
    	if(empty($permissionIdsArr)){
    		throw new \LogicException("permissionIds can not empty",60001);
    	}
    	$map = [];
    	foreach ($permissionIdsArr as $v) {
    		$tmp = [];
    		$tmp['permission_id'] = $v;
            $tmp['role_id'] = $role_id;
            $map[] = $tmp;
    	}
        return intval(RolePermissionModel::query()->insert($map));
    }


    //删除
    public static function delRolepermission($key,$whereIn)
    {
        return RolePermissionModel::query()->whereIn($key,$whereIn)->delete();
    
    }
}


    
