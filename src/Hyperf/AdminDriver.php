<?php

declare (strict_types = 1);

namespace Wuxian\Rbac\Hyperf;

use Wuxian\Rbac\Hyperf\Model\AdminModel;

class AdminDriver
{
    /**
     * 获取用户列表
     * @param array $where
     * @return array
     */
    public static function getAdminList($pageSize, $where = []) : array
    {
        return AdminModel::query()->when($where, function ($query, $where) {
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
        return AdminModel::::query()->insertGetId($data);
    }

    //更新
    public static function editAdmin($where, $data) : int
    {
        return AdminModel::query()->where($where)->update($data);
    }

    //删除
    public static function delAdmin($whereIn) : int
    {
        return AdminModel::query()->whereIn('id',$whereIn)->delete();
    }

    //获取用户信息
    public static function getAdminInfo($where) : array
    {
        $info = AdminModel::query()->where($where)->first();
        if(empty($info)){
            return [];
        }else{
            return $info->toArray();
        }
    }

    
}
