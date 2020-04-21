<?php


namespace App\Decorators\Admin;


use App\Models\Admin\Order;

/**
 * Class OrderDecorator
 * @package App\Decorators\Admin
 */
class OrderDecorator extends Order
{
    /**
     * @return int
     */
    public function getTotalSumAttribute(): int
    {
        return $this->orderProducts()->sum('price') ?? 0;
    }

    /**
     * @return array|string[]
     */
    public function getOrderStatusAttribute(): array
    {
        if ($this->status == 0) {
            return ['status' => self::ORDER_STATUS_NEW,
                'class' => 'warning',
            ];
        } elseif ($this->status == 1) {
            return ['status' => self::ORDER_STATUS_COMPLETED,
                'class' => 'success',
            ];
        } elseif ($this->status == 2) {
            return ['status' => self::ORDER_STATUS_DELETED,
                'class' => 'danger',
            ];
        } else {
            return ['status' => '',
                'class' => '',
            ];
        }
    }
}
