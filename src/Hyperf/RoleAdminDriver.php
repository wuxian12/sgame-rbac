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
        return static::$driver->newQuery()->where('role_id', $role_id)->pluck('admin_id')->toArray();
    }

    /**
     * 获取某个用户的角色id
     * @param int $admin_id 用户id
     * @return array
     */
    public static function roleIdByUserid($admin_id, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->where('admin_id', $admin_id)->pluck('role_id')->toArray();
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

    
}