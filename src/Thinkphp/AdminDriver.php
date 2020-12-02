<?php

declare (strict_types = 1);

namespace Wuxian\Rbac\Thinkphp;

use Wuxian\Rbac\Thinkphp\Model\AdminModel;
use think\Db;

class AdminDriver
{
    protected static $driver;

    public static function init($table = '')
    {
        static::$driver = new AdminModel();
        if(!empty($table)){
            static::$driver->setTable($table);
        }
        return new static();
    }
    /**
     * 获取用户列表
     * @param array $where
     * @return array
     */
    public static function getAdminList($pageSize, $where = [], $table = '')
    {
        $where[] = ['is_del', '=', 1];
        static::init($table);
        $data = static::$driver->newQuery()->when($where, function ($query, $where) {
            return $query->where($where);
        })->orderBy('id', 'desc')->paginate(intval($pageSize), ['*'], 'page')->toArray();
        if(!empty($data['data'])){
            foreach ($data['data'] as $k => $v) {
                $role_info = static::getAdminRole($v['id']);
                $data['data'][$k]['role_name'] = $role_info['role_name'];
                $data['data'][$k]['role_id'] = $role_info['role_id']; 
                
            }
        }
        return $data;
    }

    /**
     * 获取用户列表 4张表
     * @param array $where
     * @return array
     */
    public static function getAdminListFour($pageSize, $where = [], $table = '')
    {
        $role_name = RoleDriver::getRoleNameList();
        $where[] = ['is_del', '=', 1];
        static::init($table);
        $data = static::$driver->newQuery()->when($where, function ($query, $where) {
            return $query->where($where);
        })->orderBy('id', 'desc')->paginate(intval($pageSize), ['*'], 'page')->toArray();
        if(!empty($data['data'])){
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['role_name'] = $role_name[$v['role_id']] ?? '';
            }
        }
        return $data;
    }



    /**
     * 添加用户
     * @param array $data
     * @return array
     */
    public static function addAdmin($data, $table = '')
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        if(!empty(static::getAdminInfo($where))){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $role_id = $data['role_id'] ?? 0;
        unset($data['role_id']);
        Db::beginTransaction();
        try{
            $data['add_time'] = time();
            static::init($table);
            $admin_id = static::$driver->newQuery()->insertGetId($data);
            if(!empty($role_id)){
                $map['admin_id'] = $admin_id;
                $map['role_id'] = $role_id;
                RoleAdminDriver::addRoleAdmin($map);
            }
            Db::commit();
            return $admin_id;
        } catch(\Throwable $t){
            Db::rollBack();
            throw new \LogicException($t->getMessage(),60001);  
        }
        
    }

    /**
     * 添加用户  用户角色在 一张表
     * @param array $data
     * @return array
     */
    public static function addAdminFour($data, $table = '')
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        if(!empty(static::getAdminInfo($where))){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $data['add_time'] = time();
        static::init($table);
        return static::$driver->newQuery()->insertGetId($data);   
        
    }

    //更新
    public static function editAdmin($id, $data, $table = '')
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        $info = static::getAdminInfo($where);
        if(!empty($info) && $info['id'] != $id){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $role_id = $data['role_id'] ?? 0;
        unset($data['role_id']);
        Db::beginTransaction();
        try{
            $data['update_time'] = time();
            $where1 = [];
            $where1[] = ['id', '=', $id];
            static::init($table);
            $count = static::$driver->newQuery()->where($where1)->update($data);
            if(!empty($role_id)){
                //删除之前的
                RoleAdminDriver::delRoleAdmin('admin_id',[$id]);
                $map['admin_id'] = $id;
                $map['role_id'] = $role_id;
                RoleAdminDriver::addRoleAdmin($map);
            }
            Db::commit();
            return $count;
        } catch(\Throwable $t){
            Db::rollBack();
            throw new \LogicException($t->getMessage(),60001);  
        }
    }

    //更新  4张表
    public static function editAdminFour($id, $data, $table = '')
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        $info = static::getAdminInfo($where);
        if(!empty($info) && $info['id'] != $id){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        
        $data['update_time'] = time();
        $where1 = [];
        $where1[] = ['id', '=', $id];
        static::init($table);
        return static::$driver->newQuery()->where($where1)->update($data);
            
    }


    //删除
    public static function delAdmin($whereIn, $table = '')
    {
        try{
            static::init($table);
            $count = static::$driver->newQuery()->whereIn('id',$whereIn)->update(['is_del'=>2]);
            RoleAdminDriver::delRoleAdmin('admin_id',$whereIn);
            Db::commit();
            return $count; 
        } catch(\Throwable $t){
            Db::rollBack();
            throw new \LogicException($t->getMessage(),60001);  
        }
        
        
    }

    //删除  4张表
    public static function delAdminFour($whereIn, $table = '')
    {
        static::init($table);
        return static::$driver->newQuery()->whereIn('id',$whereIn)->update(['is_del'=>2]);
       
    }

    //获取用户信息
    public static function getAdminInfo($where, $table = '')
    {
        static::init($table);
        $info = static::$driver->newQuery()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            $info = $info->toArray();
            $role_info = static::getAdminRole($info['id']);
            $info['role_name'] = $role_info['role_name'];
            $info['role_id'] = $role_info['role_id'];
            
            return $info;
        }
    }

    //获取用户信息
    public static function getAdminInfoFour($where, $table = '')
    {
        static::init($table);
        $info = static::$driver->newQuery()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            $info = $info->toArray();
            $role_name = RoleDriver::getRoleNameList();
            $info['role_name'] = $role_name[$info['role_id']] ?? '';
            return $info;
        }
    }

    //获取用户角色
    public static function getAdminRole($admin_id)
    {
        $role_id = RoleAdminDriver::roleIdByUserid($admin_id);
        $role_name = RoleDriver::getRoleNameList();
        return ['role_name' => $role_name[$role_id[0] ?? 0] ?? '','role_id' => $role_id[0] ?? 0];
        
    }

    //通过用户id找角色id
    public static function getRolleId($admin_id, $table = '')
    {
        static::init($table);
        return static::$driver->newQuery()->where('id',$admin_id)->value('role_id');
       
    }

    //通过角色id找用户id
    public static function getAdminIdByRoleId($role_id, $table = '')
    {
        static::init($table);
        return static::$driver->newQuery()->where('role_id',$role_id)->pluck('id')->toArray();
       
    }

    
}
