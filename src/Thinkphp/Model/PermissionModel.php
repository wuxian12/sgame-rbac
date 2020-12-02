<?php

declare(strict_types=1);

namespace Wuxian\Rbac\Thinkphp\Model;

use think\Model;

class PermissionModel extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission';

   public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

}

