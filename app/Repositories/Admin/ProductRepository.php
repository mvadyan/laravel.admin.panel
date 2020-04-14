<?php


namespace App\Repositories\Admin;


use App\Repositories\CoreRepository;
use App\Models\Admin\Product as Model;

/**
 * Class ProductRepository
 * @package App\Repositories\Admin
 */
class ProductRepository extends CoreRepository
{
    /**
     * ProductRepository constructor.
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
    public function getLastProducts(int $perPage)
    {
        return $this->getConditions()::orderByDesc('id')
            ->limit($perPage)
            ->paginate($perPage);
    }
}
