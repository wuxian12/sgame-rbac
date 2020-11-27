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

class PermissionModel extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['identity', 'name', 'sort_order', 'parent_id', 'add_time', 'update_time', 'url','is_web'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'add_time' => 'datetime:Y-m-d H:i:s',
    ];

}

