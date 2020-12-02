<?php

declare(strict_types=1);

namespace Wuxian\Rbac\Laravel\Model;

use Illuminate\Database\Eloquent\Model;

class RoleAdminModel extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['admin_id', 'role_id'];

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

