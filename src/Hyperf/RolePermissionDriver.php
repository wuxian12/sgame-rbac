<?php
namespace Wuxian\Rbac\Hyperf;

use Wuxian\Rbac\Hyperf\Model\RolePermissionModel;

class RolePermissionDriver
{

    protected static $driver;

    public static function init($table = '', $fillable = [])
    {
        static::$driver = new RolePermissionModel();
        if(!empty($table)){
            static::$driver->setTable($table);
        }
        if(!empty($fillable)){
            static::$driver->setFillable($fillable);
        }
        return new static();
    }
    /**
     * 获取角色的权限id
     * @param array $role_id 角色id
     * @return array
     */
    public static function permissionIdByRoleids($role_ids, $table = '', $fillable = [])
    {
        static::init($table,$fillable);
        return static::$driver->newQuery()->whereIn('role_id', $role_ids)->pluck('permission_id')->toArray();
    }

    /**
     * 添加用户角色
     * @param array $data
     * @return array
     */
    public static function addRolepermission($role_id, $permissionIds, $table = '', $fillable = [])
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
        static::init($table,$fillable);
        return intval(static::$driver->newQuery()->insert($map));
    }


    //删除
    public static function delRolepermission($key,$whereIn, $table = '', $fillable = [])
    {
        static::init($table,$fillable);
        return static::$driver->newQuery()->whereIn($key,$whereIn)->delete();
    
    }
}


    
