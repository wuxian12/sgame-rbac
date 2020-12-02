<?php
namespace Wuxian\Rbac\Thinkphp;

use Wuxian\Rbac\Thinkphp\Model\PermissionModel;

class PermissionDriver
{

    protected static $driver;

    public static function init($config = [])
    {
        if(empty(static::$driver)){
            static::$driver = new PermissionModel();
            if(!empty($config['permission_table'] ?? '')){
                static::$driver->setTable($config['permission_table']);
            }
        }
        
        return new static();
    }
    /**
     * 获取权限列表
     * @param array $permission_ids 权限id
     * @return array
     */
    public static function getPermissionList($where = [],$permission_ids = [], $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->when($where, function ($query, $where) {
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
    public static function addPermission($data, $config = [])
    {
        $where = [];
        $where[] = ['name', '=', $data['name'] ?? ''];
        if(!empty(static::getPermissionInfo($where, $config))){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $data['add_time'] = time();
        static::init($config);
        return static::$driver->newQuery()->insertGetId($data);
    }

    //更新
    public static function editPermission($id, $data, $config = [])
    {
        $where = [];
        $where[] = ['name', '=', $data['name'] ?? ''];
        $info = static::getPermissionInfo($where, $config);
        if(!empty($info) && $info['id'] != $id){
            throw new \LogicException("name is duplicate,please update name",60001);  
        }
        $data['update_time'] = time();
        $where1 = [];
        $where1[] = ['id', '=', $id];
        static::init($config);
        return static::$driver->newQuery()->where($where1)->update($data);
    }

    //删除
    public static function delPermission($whereIn, $config = [])
    {
        static::init($config);
        return static::$driver->newQuery()->whereIn('id',$whereIn)->delete();
    }

    //删除
    public static function getPermissionInfo($where, $config = [])
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
