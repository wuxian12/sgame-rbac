<?php

declare (strict_types = 1);

namespace Wuxian\Rbac;

class RoleAdmin extends CommonAbstract
{
    /**
     * 模型名字
     * @var array
     */
    public $modelName = 'RoleAdminModel';

    /**
     * 模型驱动
     * @var array
     */
    public $modelDriver = 'RoleAdminDriver';
    

}