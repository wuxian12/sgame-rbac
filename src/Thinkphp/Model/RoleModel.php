<?php

declare(strict_types=1);

namespace Wuxian\Rbac\Thinkphp\Model;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role';

   public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }
    
}
