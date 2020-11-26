<?php
namespace Wuxian\Rbac;

use App\Model\Role;

class Role
{
	//所有角色列表
    public static function roleAll($role_ids = []) : array
    {
        return Role::query()->when($role_ids, function ($query, $role_ids) {
            return $query->whereIn('id', $role_ids);
        })->get()->toArray();
    }

    /**
     * 获取角色列表
     * @param array $permission_ids 权限id
     * @return array
     */
    public static function roleList($pageSize, $where = []) : array
    {
        return Role::query()->where($where)->paginate(intval($pageSize), ['*'], 'page')->toArray();
    }

    /**
     * 添加角色
     * @param array $data
     * @return array
     */
    public static function addRole($data) : int
    {
        return Role::::query()->insertGetId($data);
    }

    //更新
    public static function editRole($where, $data) : int
    {
        return Role::query()->where($where)->update($data);
    }

    //删除
    public static function delRole($whereIn) : int
    {
        return Role::query()->whereIn('id',$whereIn)->delete();
    }

    //获取角色信息
    public static function getRoleInfo($where) : array
    {
        $info = Role::query()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            return $info->toArray();
        }
    }
}
