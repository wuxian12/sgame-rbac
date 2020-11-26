<?php
namespace Wuxian\Rbac;

class Permission
{

    // 对应的 model
    protected $modelQuery;

    /**
     * 获取指定模型获取数据
     * @param array $permission_ids 权限id
     * @return array
     */
    public static function getModel($modelName) : array
    {
        $this->modelQuery = '\\Wuxian\\Rbac\\'.ucfirst($modelName).'\\Permission';
        return new self();
    }
    /**
     * 获取权限列表
     * @param array $permission_ids 权限id
     * @return array
     */
    public function getPermissionList($permission_ids = []) : array
    {
        return $this->modelQuery::query()->when($permission_ids, function ($query, $permission_ids) {
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
