<?php


namespace App\Repositories\Admin;


use App\Repositories\CoreRepository;
use App\Models\Admin\Order as Model;

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
     * @param int $perPage
     * @return mixed
     */
    public function getAllOrders(int $perPage)
    {
        return $this->getConditions()::withTrashed()
            ->has('user')
            ->with('user')
            ->orderBy('status')
            ->orderBy('id')
            ->paginate($perPage);
    }

}
