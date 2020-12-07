<?php
namespace Wuxian\Rbac\Thinkphp;

use Wuxian\Rbac\Thinkphp\Model\RoleModel;

class RoleDriver
{
	protected static $driver;

    public static function init($config = [])
    {
        if(empty(static::$driver)){
            static::$driver = new RoleModel();
            if(!empty($config['role_table'] ?? '')){
                static::$driver->setTable($config['role_table']);
            }
        }
        
        return new static();
    }
    //所有角色列表
    public static function roleAll($role_ids = [], $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->where($config['false_delete_key'],1)->when($role_ids, function ($query, $role_ids) {
            return $query->whereIn('id', $role_ids);
        })->get()->toArray();
        
    }

    /**
     * 获取角色列表
     * @param array $permission_ids 权限id
     * @return array
     */
    public static function roleList($pageSize, $where = [], $config = [])
    {
        $where[] = [$config['false_delete_key'], '=', 1];
        static::init($config);
        return static::$driver->newQuery()->where($where)->paginate(intval($pageSize), ['*'], 'page')->toArray();
    }


    //获取角色名字
    public static function getRoleNameList($config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->pluck($config['role_table_duplicate'],'id')->toArray();
    
    }

    /**
     * 添加角色
     * @param array $data
     * @return array
     */
    public static function addRole($data, $config = [])
    {
        $where = [];
        $where[] = [$config['false_delete_key'], '=', 1];
        $where[] = [$config['role_table_duplicate'], '=', $data[$config['role_table_duplicate']] ?? ''];
        if(!empty(static::getRoleInfo($where,$config))){
            throw new \LogicException($config['role_table_duplicate']." is duplicate,please update",60001);  
        }
        $data['add_time'] = time();
        static::init($config);
        return static::$driver->newQuery()->insertGetId($data);
    }

    //更新
    public static function editRole($id, $data, $config = [])
    {
        $where = [];
        $where[] = [$config['false_delete_key'], '=', 1];
        $where[] = [$config['role_table_duplicate'], '=', $data[$config['role_table_duplicate']] ?? ''];
        $info = static::getRoleInfo($where,$config);
        if(!empty($info) && $info['id'] != $id){
            throw new \LogicException($config['role_table_duplicate']." is duplicate,please update",60001); 
        }
        $data['update_time'] = time();
        $where1 = [];
        $where1[] = ['id', '=', $id];
        static::init($config);
        return static::$driver->newQuery()->where($where1)->update($data);
    }

    //删除
    public static function delRole($whereIn, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->whereIn('id',$whereIn)->update([$config['false_delete_key']=>2]);
        //return RoleModel::query()->whereIn('id',$whereIn)->delete();
    }

    //获取角色信息
    public static function getRoleInfo($where, $config = [])
    {
        static::init($config);
        $info = static::$driver->newQuery()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            return $info->toArray();
        }
    }
}
