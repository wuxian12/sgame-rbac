<?php

declare (strict_types = 1);

namespace Wuxian\Rbac\Hyperf;

use Wuxian\Rbac\Hyperf\Model\AdminModel;
use Hyperf\DbConnection\Db;

class AdminDriver
{
    /**
     * 获取用户列表
     * @param array $where
     * @return array
     */
    public static function getAdminList($pageSize, $where = []) : array
    {
        $role_id = RoleAdminDriver::roleIdByUserid(13);
        $where[] = ['is_del', '=', 1];
        $role_name = RoleDriver::getRoleNameList();
        $data = AdminModel::query()->with('role')->when($where, function ($query, $where) {
            return $query->where($where);
        })->orderBy('id', 'desc')->paginate(intval($pageSize), ['*'], 'page')->toArray();
        if(!empty($data['data'])){
            foreach ($data['data'] as $k => $v) {
                $role_info = static::getAdminRole($v['id']);
                $data['data'][$k]['role_name'] = $role_info['role_name'];
                $data['data'][$k]['role_id'] = $role_info['role_id']; 
                unset($data['data'][$k]['role']);
            }
        }
        return $data;
    }

    /**
     * 添加用户
     * @param array $data
     * @return array
     */
    public static function addAdmin($data) : int
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
            $admin_id = AdminModel::query()->insertGetId($data);
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

    //更新
    public static function editAdmin($id, $data) : int
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
            $count = AdminModel::query()->where($where1)->update($data);
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

    //删除
    public static function delAdmin($whereIn) : int
    {
        try{
            $count = AdminModel::query()->whereIn('id',$whereIn)->update(['is_del'=>2]);
            RoleAdminDriver::delRoleAdmin('admin_id',$whereIn);
            Db::commit();
            return $count; 
        } catch(\Throwable $t){
            Db::rollBack();
            throw new \LogicException($t->getMessage(),60001);  
        }
        
        
    }

    //获取用户信息
    public static function getAdminInfo($where) : array
    {
        $info = AdminModel::query()->with('role')->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            $info = $info->toArray();
            $role_info = static::getAdminRole($info['id']);
            $info['role_name'] = $role_info['role_name'];
            $info['role_id'] = $role_info['role_id'];
            unset($info['role']);
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

    
}
