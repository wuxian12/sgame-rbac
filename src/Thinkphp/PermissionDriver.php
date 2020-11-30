<?php
namespace Wuxian\Rbac\Thinkphp;

use Wuxian\Rbac\Thinkphp\Model\PermissionModel;

class PermissionDriver
{

    /**
     * 获取权限列表
     * @param array $permission_ids 权限id
     * @return array
     */
    public static function getPermissionList($where = [],$permission_ids = [])
    {
        return PermissionModel::query()->when($where, function ($query, $where) {
            return $query->where($where);
        })->when($permission_ids, function ($query, $permission_ids) {
            return $query->whereIn('id', $permission_ids);
        })->orderBy('sort_order', 'desc')->get()->toArray();
    }

    /**
     * 添加权限
     * @param array $data
     * @return array
     */
    public static function addPermission($data)
    {
        $where = [];
        $where[] = ['name', '=', $data['name'] ?? ''];
        if(!empty(static::getPermissionInfo($where))){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $data['add_time'] = time();
        return PermissionModel::query()->insertGetId($data);
    }

    //更新
    public static function editPermission($id, $data)
    {
        $where = [];
        $where[] = ['name', '=', $data['name'] ?? ''];
        $info = static::getPermissionInfo($where);
        if(!empty($info) && $info['id'] != $id){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $data['update_time'] = time();
        $where1 = [];
        $where1[] = ['id', '=', $id];
        return PermissionModel::query()->where($where1)->update($data);
    }

    //删除
    public static function delPermission($whereIn)
    {
        return PermissionModel::query()->whereIn('id',$whereIn)->delete();
    }

    //删除
    public static function getPermissionInfo($where)
    {
        $info = PermissionModel::query()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            return $info->toArray();
        }
    }


}
