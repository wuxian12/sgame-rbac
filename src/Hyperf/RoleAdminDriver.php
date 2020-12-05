<?php

declare (strict_types = 1);

namespace Wuxian\Rbac\Hyperf;

use Wuxian\Rbac\Hyperf\Model\RoleAdminModel;

class RoleAdminDriver
{
    protected static $driver;

    public static function init($config = [])
    {
        if(empty(static::$driver)){
            static::$driver = new RoleAdminModel();
            if(!empty($config['role_admin_table'] ?? '')){
                static::$driver->setTable($config['role_admin_table']);
            }
            if(!empty($config['role_admin_fillable'] ?? '')){
                static::$driver->setFillable($config['role_admin_fillable']);
            }
           
        }
        
        return new static();
    }

    //通过某角色id获取相应用户id
    public static function adminIdsByRoleId($role_id, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->where($config['admin_role_table_role_id'], $role_id)->pluck($config['admin_role_table_admin_id'])->toArray();
    }

    /**
     * 获取某个用户的角色id
     * @param int $admin_id 用户id
     * @return array
     */
    public static function roleIdByUserid($admin_id, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->where($config['admin_role_table_admin_id'], $admin_id)->pluck($config['admin_role_table_role_id'])->toArray();
    }

    /**
     * 添加用户角色
     * @param array $data
     * @return array
     */
    public static function addRoleAdmin($data, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->insert($data);
    }


    //删除
    public static function delRoleAdmin($key,$whereIn, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->whereIn($key,$whereIn)->delete();
    
    }

    /**
     * 添加用户角色
     */
    public static function addAdminIdRoleIds($adminId, $roleIds, $config = [])
    {
        //删除之前的
        static::delRoleAdmin($config['admin_role_table_admin_id'],[$adminId],$config);
        $roleIdsArr = explode(',', $roleIds);
        if(empty($roleIdsArr)){
            throw new \LogicException("roleIds can not empty",60001);
        }
        $map = [];
        foreach ($roleIdsArr as $v) {
            $tmp = [];
            $tmp[$config['admin_role_table_role_id']] = $v;
            $tmp[$config['admin_role_table_admin_id']] = $adminId;
            $map[] = $tmp;
        }
        static::init($config);
        return intval(static::$driver->newQuery()->insert($map));
    }

    /**
     * 添加角色用户
     */
    public static function addRoleIdAdminIds($roleId, $adminIds, $config = [])
    {
        //删除之前的
        static::delRoleAdmin($config['admin_role_table_role_id'],[$roleId],$config);
        $adminIdsArr = explode(',', $adminIds);
        if(empty($adminIdsArr)){
            throw new \LogicException("adminIds can not empty",60001);
        }
        $map = [];
        foreach ($roleIdsArr as $v) {
            $tmp = [];
            $tmp[$config['admin_role_table_admin_id']] = $v;
            $tmp[$config['admin_role_table_role_id']] = $roleId;
            $map[] = $tmp;
        }
        static::init($config);
        return intval(static::$driver->newQuery()->insert($map));
    }

    
}