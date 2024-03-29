<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    const ORDER_STATUS_NEW = 'Новый';
    const ORDER_STATUS_COMPLETED = 'Обработан';
    const ORDER_STATUS_DELETED = 'Удален';

    protected $table = 'orders';

    protected $fillable = [
        'id',
        'user_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'currency',
        'note',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany('App\Models\Admin\OrderProducts', 'order_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Admin\User');
    }
}
