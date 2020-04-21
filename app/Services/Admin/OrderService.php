<?php


namespace App\Services\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Order;


/**
 * Class OrderService
 * @package App\Services\Admin
 */
class OrderService
{
    /**
     * @var Request
     */
    private $request;

    /**
     * OrderService constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    /**
     * @param Order $order
     * @param $status
     * @return Order
     */
    public function changeOrderStatus(Order $order, $status)
    {
        $order->update([
            'status' => $status,
        ]);

        return $order;
    }

    /**
     * @param Order $order
     * @param $id
     * @return Order
     */
    public function orderDestroy(Order $order, $id)
    {
        $order::destroy($id);

        return $order;
    }

    /**
     * @param Order $order
     * @return Order
     */
    public function saveOrderComment(Order $order)
    {
        $order->update([
            'note' => $this->request->input('comment'),
        ]);

        return $order;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function orderDelete($id)
    {
        $order =  Order::withTrashed()->find($id);
        $order->orderProducts()->delete();
        $order->forceDelete();

        return $order;
    }
}
