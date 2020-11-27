<?php
namespace Wuxian\Rbac\Hyperf;

use Wuxian\Rbac\Hyperf\Model\RoleModel;

class RoleDriver
{
	//所有角色列表
    public static function roleAll($role_ids = []) : array
    {
        return RoleModel::query()->where('is_del',1)->when($role_ids, function ($query, $role_ids) {
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
        $where[] = ['is_del', '=', 1];
        return RoleModel::query()->where($where)->paginate(intval($pageSize), ['*'], 'page')->toArray();
    }

    /**
     * 添加角色
     * @param array $data
     * @return array
     */
    public static function addRole($data) : int
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        if(!empty(static::getRoleInfo($where))){
            throw new LogicException("name is duplicate,please update name",60001);  
        }
        $data['add_time'] = time();
        return RoleModel::::query()->insertGetId($data);
    }

    //更新
    public static function editRole($id, $data) : int
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        $info = static::getRoleInfo($where);
        if(!empty($info) && $info['id'] != $id){
            throw new LogicException("name is duplicate,please update name",60001);  
        }
        $data['update_time'] = time();
        $where1 = [];
        $where1[] = ['id', '=', $id];
        return RoleModel::query()->where($where1)->update($data);
    }

    //删除
    public static function delRole($whereIn) : int
    {
        return RoleModel::query()->whereIn('id',$whereIn)->delete();
    }

    //获取角色信息
    public static function getRoleInfo($where) : array
    {
        $info = RoleModel::query()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            return $info->toArray();
        }
    }
}
