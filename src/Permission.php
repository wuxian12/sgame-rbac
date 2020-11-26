<?php
namespace Wuxian\Rbac;

use App\Model\Permission;


class Permission
{

    /**
     * 获取权限列表
     * @param array $permission_ids 权限id
     * @return array
     */
    public static function getPermissionList($permission_ids = []) : array
    {
        return Permission::query()->when($permission_ids, function ($query, $permission_ids) {
            return $query->whereIn('id', $permission_ids);
        })->orderBy('sort_order', 'desc')->get()->toArray();
    }

    /**
     * 添加权限
     * @param array $data
     * @return array
     */
    public static function addPermission($data) : int
    {
        return Permission::::query()->insertGetId($data);
    }

    //更新
    public static function editPermission($where, $data) : int
    {
        return Permission::query()->where($where)->update($data);
    }

    //删除
    public static function delPermission($whereIn) : int
    {
        return Permission::query()->whereIn('id',$whereIn)->delete();
    }

    //删除
    public static function getPermissionInfo($where) : array
    {
        $info = Permission::query()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            return $info->toArray();
        }
    }

    

}
