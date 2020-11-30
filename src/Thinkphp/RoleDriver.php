<?php
namespace Wuxian\Rbac\Thinkphp;

use Wuxian\Rbac\Thinkphp\Model\RoleModel;

class RoleDriver
{
	//所有角色列表
    public static function roleAll($role_ids = [])
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
    public static function roleList($pageSize, $where = [])
    {
        $where[] = ['is_del', '=', 1];
        return RoleModel::query()->where($where)->paginate(intval($pageSize), ['*'], 'page')->toArray();
    }


    //获取角色名字
    public static function getRoleNameList()
    {
        return RoleModel::query()->pluck('name','id')->toArray();
    
    }

    /**
     * 添加角色
     * @param array $data
     * @return array
     */
    public static function addRole($data)
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        if(!empty(static::getRoleInfo($where))){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $data['add_time'] = time();
        return RoleModel::query()->insertGetId($data);
    }

    //更新
    public static function editRole($id, $data)
    {
        $where = [];
        $where[] = ['is_del', '=', 1];
        $where[] = ['name', '=', $data['name'] ?? ''];
        $info = static::getRoleInfo($where);
        if(!empty($info) && $info['id'] != $id){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $data['update_time'] = time();
        $where1 = [];
        $where1[] = ['id', '=', $id];
        return RoleModel::query()->where($where1)->update($data);
    }

    //删除
    public static function delRole($whereIn)
    {
        return RoleModel::query()->whereIn('id',$whereIn)->update(['is_del'=>2]);
        //return RoleModel::query()->whereIn('id',$whereIn)->delete();
    }

    //获取角色信息
    public static function getRoleInfo($where)
    {
        $info = RoleModel::query()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            return $info->toArray();
        }
    }
}
