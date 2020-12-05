<?php
namespace Wuxian\Rbac\Thinkphp;

use Wuxian\Rbac\Thinkphp\Model\RolePermissionModel;

class RolePermissionDriver
{
    protected static $driver;

    public static function init($config = [])
    {
        if(empty(static::$driver)){
            static::$driver = new RolePermissionModel();
            if(!empty($config['role_permission_table'] ?? '')){
                static::$driver->setTable($config['role_permission_table']);
            }
            if(!empty($config['role_permission_fillable'] ?? '')){
                static::$driver->setFillable($config['role_permission_fillable']);
            }
           
        }
        return new static();
    }
    /**
     * 获取角色的权限id
     * @param array $role_id 角色id
     * @return array
     */
    public static function permissionIdByRoleids($role_ids, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->whereIn($config['role_permission_table_role_id'], $role_ids)->pluck($config['role_permission_table_permission_id'])->toArray();
    }

    /**
     * 添加角色权限
     * @param array $data
     * @return array
     */
    public static function addRolepermission($role_id, $permissionIds, $config = [])
    {
        //删除之前的
        static::delRolepermission($config['role_permission_table_role_id'],[$role_id],$config);
        $permissionIdsArr = explode(',', $permissionIds);
        if(empty($permissionIdsArr)){
            throw new \LogicException("permissionIds can not empty",60001);
        }
        $map = [];
        foreach ($permissionIdsArr as $v) {
            $tmp = [];
            $tmp[$config['role_permission_table_permission_id']] = $v;
            $tmp[$config['role_permission_table_role_id']] = $role_id;
            $map[] = $tmp;
        }
        static::init($config);
        return intval(static::$driver->newQuery()->insert($map));
    }


    //删除
    public static function delRolepermission($key,$whereIn, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->whereIn($key,$whereIn)->delete();
    
    }
}


    
