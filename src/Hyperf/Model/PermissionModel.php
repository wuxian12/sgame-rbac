<?php

declare(strict_types=1);

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

    public function setFillable($fillable)
    {
        $this->fillable = $fillable;
        return $this;
    }

}

