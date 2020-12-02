<?php

declare (strict_types = 1);

namespace Wuxian\Rbac\Hyperf;

use Wuxian\Rbac\Hyperf\Model\RoleAdminModel;

class RoleAdminDriver
{
    protected static $driver;

    public static function init($table = '', $fillable = [])
    {
        static::$driver = new RoleAdminModel();
        if(!empty($table)){
            static::$driver->setTable($table);
        }
        if(!empty($fillable)){
            static::$driver->setFillable($fillable);
        }
        return new static();
    }

    //通过某角色id获取相应用户id
    public static function adminIdsByRoleId($role_id, $table = '', $fillable = [])
    {
        static::init($table,$fillable);
        return static::$driver->newQuery()->where('role_id', $role_id)->pluck('admin_id')->toArray();
    }

    /**
     * 获取某个用户的角色id
     * @param int $admin_id 用户id
     * @return array
     */
    public static function roleIdByUserid($admin_id, $table = '', $fillable = [])
    {
        static::init($table,$fillable);
        return static::$driver->newQuery()->where('admin_id', $admin_id)->pluck('role_id')->toArray();
    }

    /**
     * 添加用户角色
     * @param array $data
     * @return array
     */
    public static function addRoleAdmin($data, $table = '', $fillable = [])
    {
        static::init($table,$fillable);
        return static::$driver->newQuery()->insert($data);
    }


    //删除
    public static function delRoleAdmin($key,$whereIn, $table = '', $fillable = [])
    {
        static::init($table,$fillable);
        return static::$driver->newQuery()->whereIn($key,$whereIn)->delete();
    
    }

    
}