<?php

declare(strict_types=1);

namespace Wuxian\Rbac\Laravel\Model;

use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'add_time', 'update_time', 'status', 'password', 'remark', 'last_login_time','is_del'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'add_time' => 'datetime:Y-m-d H:i:s',
    ];

    public function role()
    {
        return $this->hasOne(RoleAdminModel::class, 'admin_id', 'id');
    }

    public function setFillable($fillable)
    {
        $this->fillable = $fillable;
        return $this;
    }
}

