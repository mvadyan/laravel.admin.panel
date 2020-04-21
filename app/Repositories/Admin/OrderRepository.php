<?php


namespace App\Repositories\Admin;

use App\Repositories\CoreRepository;
use App\Decorators\Admin\OrderDecorator as Model;

/**
 * Class OrderRepository
 * @package App\Repositories\Admin
 */
class OrderRepository extends CoreRepository
{

    /**
     * OrderRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed|string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getId($id)
    {
        return $this->getConditions()::withTrashed()->findOrFail($id);
    }

    /**
     * @param int $perPage
     * @return mixed
     */
    public function getAllOrders(int $perPage)
    {
        return $this->getConditions()::withTrashed()
            ->has('user')
            ->with('user')
            ->orderBy('id')
            ->paginate($perPage);
    }

    /**
     * @param int $order_id
     * @return mixed
     */
    public function getOneOrder(int $order_id)
    {
        return $this->getConditions()::withTrashed()
            ->has('user')
            ->has('orderProducts')
            ->with(['user', 'orderProducts'])
            ->withCount('orderProducts')
            ->findOrFail($order_id);
    }

}
