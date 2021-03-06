<?php

declare(strict_types=1);

namespace Wuxian\Rbac\Laravel\Model;

use Illuminate\Database\Eloquent\Model;

class RolePermissionModel extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_permission';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['permission_id', 'role_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function setFillable($fillable)
    {
        $this->fillable = $fillable;
        return $this;
    }
}
