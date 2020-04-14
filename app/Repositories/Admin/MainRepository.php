<?php


namespace App\Repositories\Admin;


use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class MainRepository
 * @package App\Repositories\Admin
 */
class MainRepository extends CoreRepository
{

    /**
     * @return mixed|string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @return int
     */
    public static function getCountOrders(): int
    {
        return DB::table('orders')
                ->where('status', '0')
                ->get()
                ->count() ?? 0;
    }

    /**
     * @return int
     */
    public static function getCountUsers(): int
    {
        return DB::table('users')
                ->get()
                ->count() ?? 0;
    }

    /**
     * @return int
     */
    public static function getCountProducts(): int
    {
        return DB::table('products')
                ->get()
                ->count() ?? 0;
    }

    /**
     * @return int
     */
    public static function getCountCategories(): int
    {
        return DB::table('categories')
                ->get()
                ->count() ?? 0;
    }
}
