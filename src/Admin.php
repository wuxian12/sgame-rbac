<?php

declare (strict_types = 1);

namespace Wuxian\Rbac;

use App\Model\AuthPermission as Permission;
use App\Model\AuthRole as Role;
use App\Model\AuthEmployee as RoleAdmin; // 待定和账号绑定的角色管理
use App\Model\AuthRolePermission as RolePermission;

class Admin
{
    /**
     * 获取用户列表
     * @param array $where
     * @return array
     */
    public static function getAdminList($pageSize, $where = []) : array
    {
        return Admin::query()->when($where, function ($query, $where) {
            return $query->where($where);
        })->orderBy('id', 'desc')->paginate(intval($pageSize), ['*'], 'page')->toArray();
    }

    /**
     * 添加用户
     * @param array $data
     * @return array
     */
    public static function addAdmin($data) : int
    {
        return Admin::::query()->insertGetId($data);
    }

    //更新
    public static function editAdmin($where, $data) : int
    {
        return Admin::query()->where($where)->update($data);
    }

    //删除
    public static function delAdmin($whereIn) : int
    {
        return Admin::query()->whereIn('id',$whereIn)->delete();
    }

    //获取用户信息
    public static function getAdminInfo($where) : array
    {
        $info = Admin::query()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            return $info->toArray();
        }
    }

    
}
