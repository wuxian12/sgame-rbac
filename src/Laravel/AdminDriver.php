<?php

declare (strict_types = 1);

namespace Wuxian\Rbac\Laravel;

use Wuxian\Rbac\Laravel\Model\AdminModel;
use Illuminate\Support\Facades\DB as Db;

class AdminDriver
{

    protected static $driver;

    public static function init($config = [])
    {
        if(empty(static::$driver)){
            static::$driver = new AdminModel();
            if(!empty($config['admin_table'] ?? '')){
                static::$driver->setTable($config['admin_table']);
            }
        }
        return new static();
    }
    /**
     * 获取用户列表
     * @param array $where
     * @return array
     */
    public static function getAdminList($pageSize, $where = [], $config = [])
    {
        $where[] = ['is_del', '=', 1];
        static::init($config);
        $data = static::$driver->newQuery()->when($where, function ($query, $where) {
            return $query->where($where);
        })->orderBy('id', 'desc')->paginate(intval($pageSize), ['*'], 'page')->toArray();
        if(!empty($data['data'])){
            foreach ($data['data'] as $k => $v) {
                $role_info = static::getAdminRole($v['id'],$config);
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
    public static function getAdminListFour($pageSize, $where = [], $config = [])
    {
        $role_name = RoleDriver::getRoleNameList($config);
        $where[] = ['is_del', '=', 1];
        static::init($config);
        $data = static::$driver->newQuery()->when($where, function ($query, $where) {
            return $query->where($where);
        })->orderBy('id', 'desc')->paginate(intval($pageSize), ['*'], 'page')->toArray();
        if(!empty($data['data'])){
            foreach ($data['data'] as $k => $v) {
                $data['data'][$k]['role_name'] = $role_name[$v[$config['admin_table_role_id']]] ?? '';
            }
        }
        return $data;
    }



    /**
     * 添加用户
     * @param array $data
     * @return array
     */
    public static function addAdmin($data, $config = [])
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        if(!empty(static::getAdminInfo($where,$config))){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $role_id = $data['role_id'] ?? 0;
        unset($data['role_id']);
        Db::beginTransaction();
        try{
            $data['add_time'] = time();
            static::init($config);
            $admin_id = static::$driver->newQuery()->insertGetId($data);
            if(!empty($role_id)){
                $map = [];
                $role_arr = explode(',', $role_id);
                foreach ($role_arr as $v) {
                    $tmp = [];
                    $tmp[$config['admin_role_table_admin_id']] = $admin_id;
                    $tmp[$config['admin_role_table_role_id']] = $v;
                    $map[] = $tmp;
                }
                RoleAdminDriver::addRoleAdmin($map,$config);
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
    public static function addAdminFour($data, $config = [])
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        if(!empty(static::getAdminInfo($where,$config))){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $data['add_time'] = time();
        static::init($config);
        return static::$driver->newQuery()->insertGetId($data);   
        
    }

    //更新
    public static function editAdmin($id, $data, $config = [])
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        $info = static::getAdminInfo($where,$config);
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
            static::init($config);
            $count = static::$driver->newQuery()->where($where1)->update($data);
            if(!empty($role_id)){
                //删除之前的
                RoleAdminDriver::delRoleAdmin($config['admin_role_table_admin_id'],[$id],$config);
                $map[$config['admin_role_table_admin_id']] = $id;
                $map[$config['admin_role_table_role_id']] = $role_id;
                RoleAdminDriver::addRoleAdmin($map,$config);
            }
            Db::commit();
            return $count;
        } catch(\Throwable $t){
            Db::rollBack();
            throw new \LogicException($t->getMessage(),60001);  
        }
    }

    //更新  4张表
    public static function editAdminFour($id, $data, $config = [])
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        $info = static::getAdminInfo($where,$config);
        if(!empty($info) && $info['id'] != $id){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        
        $data['update_time'] = time();
        $where1 = [];
        $where1[] = ['id', '=', $id];
        static::init($config);
        return static::$driver->newQuery()->where($where1)->update($data);
            
    }


    //删除
    public static function delAdmin($whereIn, $config = [])
    {
        try{
            static::init($config);
            $count = static::$driver->newQuery()->whereIn('id',$whereIn)->update(['is_del'=>2]);
            RoleAdminDriver::delRoleAdmin($config['admin_role_table_admin_id'],$whereIn,$config);
            Db::commit();
            return $count; 
        } catch(\Throwable $t){
            Db::rollBack();
            throw new \LogicException($t->getMessage(),60001);  
        }
        
        
    }

    //删除  4张表
    public static function delAdminFour($whereIn, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->whereIn('id',$whereIn)->update(['is_del'=>2]);
       
    }

    //获取用户信息
    public static function getAdminInfo($where, $config = [])
    {
        static::init($config);
        $info = static::$driver->newQuery()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            $info = $info->toArray();
            $role_info = static::getAdminRole($info['id'],$config);
            $info['role_name'] = $role_info['role_name'];
            $info['role_id'] = $role_info['role_id'];
            
            return $info;
        }
    }

    //获取用户信息
    public static function getAdminInfoFour($where, $config = [])
    {
        static::init($config);
        $info = static::$driver->newQuery()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            $info = $info->toArray();
            $role_name = RoleDriver::getRoleNameList();
            $info['role_name'] = $role_name[$info[$config['admin_table_role_id']]] ?? '';
            return $info;
        }
    }

    //获取用户角色
    public static function getAdminRole($admin_id, $config = [])
    {
        $role_id = RoleAdminDriver::roleIdByUserid($admin_id,$config);
        $role_name = RoleDriver::getRoleNameList($config);
        return ['role_name' => $role_name[$role_id[0] ?? 0] ?? '','role_id' => $role_id[0] ?? 0];
        
    }

    //通过用户id找角色id
    public static function getRolleId($admin_id, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->where('id',$admin_id)->value($config['admin_table_role_id']);
       
    }

    //通过角色id找用户id
    public static function getAdminIdByRoleId($role_id, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->where($config['admin_table_role_id'],$role_id)->pluck('id')->toArray();
       
    }


    
}
