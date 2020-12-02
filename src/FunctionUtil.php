<?php

namespace Wuxian\Rbac;

class FunctionUtil
{
	//递归实现侧边栏(层级从属关系)
    public static function getTree(array $data, $pid = 0, $level = 1) : array
    {
        $list = [];
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $pid) {
                $v['level'] = $level;
                $v['son'] = static::getTree($data, $v['id'], $level + 1);
                $list[] = $v;
            }
        }
        return $list;
    }

    //获取默认配置
    public static function getDefualtConfig() : array
    {
        return [
            'type' => 'hyperf',  //框架类型
            'super' => [1],   //超级管理员id
            'permission_model' => '', //自定义的权限模型
            'admin_model' => '', //自定义的管理员模型
            'role_model' => '', //自定义的角色模型
            'role_admin_model' => '', //自定义的角色用户模型
            'role_permission_model' => '', //自定义的角色权限模型
            'table_num' => 4,  //表的数量 角色用户一对一可以是4张表也可以5张,角色用户多对多就是5张表
            'permission_table' => '', //权限表名
            'admin_table' => '', //用户表名
            'role_table' => '', //角色表名
            'role_admin_table' => '', //角色用户表名
            'role_permission_table' => '', //角色权限表名
            'permission_fillable' => [], //权限表结构
            'admin_fillable' => [], //用户表结构
            'role_fillable' => [], //角色表结构
            'role_admin_fillable' => [], //角色用户表结构
            'role_permission_fillable' => [], //角色权限表结构
        ];
    }
}