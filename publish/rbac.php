<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'type' => 'hyperf',  //框架类型
    'super' => [1],   //超级管理员id
    'permission_model' => '', //自定义的权限模型
    'admin_model' => '', //自定义的管理员模型
    'role_model' => '', //自定义的角色模型
    'role_admin_model' => '', //自定义的角色用户模型
    'role_permission_model' => '', //自定义的角色权限模型
    'table_num' => 4,  //表的数量 角色用户一对一可以是4张表也可以5张,角色用户多对多就是5张表
];
