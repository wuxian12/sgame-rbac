<?php

declare(strict_types=1);

namespace Wuxian\Rbac\Thinkphp\Model;

use think\Model;

class AdminModel extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    public function role()
    {
        return $this->hasMany(RoleAdminModel::class, 'admin_id', 'id');
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }
}

