<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */
namespace Wuxian\Rbac\Hyperf\Model;

use Hyperf\DbConnection\Model\Model;

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
}

