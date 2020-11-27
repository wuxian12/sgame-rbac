<?php
namespace Wuxian\Rbac\Hyperf;

use Wuxian\Rbac\Hyperf\Model\PermissionModel;

class PermissionDriver
{

    /**
     * 获取权限列表
     * @param array $permission_ids 权限id
     * @return array
     */
    public function getPermissionList($permission_ids = []) : array
    {
        return PermissionModel::query()->when($permission_ids, function ($query, $permission_ids) {
            return $query->whereIn('id', $permission_ids);
        })->orderBy('sort_order', 'desc')->get()->toArray();
    }

    /**
     * 添加权限
     * @param array $data
     * @return array
     */
    public function addPermission($data) : int
    {
        return PermissionModel::::query()->insertGetId($data);
    }

    //更新
    public static function editPermission($where, $data) : int
    {
        return PermissionModel::query()->where($where)->update($data);
    }

    //删除
    public static function delPermission($whereIn) : int
    {
        return PermissionModel::query()->whereIn('id',$whereIn)->delete();
    }

    //删除
    public static function getPermissionInfo($where) : array
    {
        $info = PermissionModel::query()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            return $info->toArray();
        }
    }


}
