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
}