<?php


namespace App\Repositories\Admin;


use App\Decorators\Admin\OrderDecorator;
use App\Models\Admin\User;
use App\Repositories\CoreRepository;
use App\Models\Admin\User as Model;

/**
 * Class UserRepository
 * @package App\Repositories\Admin
 */
class UserRepository extends CoreRepository
{

    /**
     * UserRepository constructor.
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
     * @param $perPage
     * @return mixed
     */
    public function getAllUsers($perPage)
    {
        return $this->getConditions()
            ->with('roles')
            ->orderBy('users.id')
            ->paginate($perPage);
    }

    /**
     * @param int $perPage
     * @param int $user_id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserOrders(int $perPage, int $user_id)
    {
        return OrderDecorator::withTrashed()
            ->where('user_id', $user_id)
            ->orderBy('id')
            ->paginate($perPage);

    }
}
