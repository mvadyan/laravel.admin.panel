<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Repositories\Admin\MainRepository;
use App\Repositories\Admin\OrderRepository;
use App\Repositories\Admin\ProductRepository;
use MetaTag;

/**
 * Class MainController
 * @package App\Http\Controllers\Blog\Admin
 */
class MainController extends AdminBaseController
{
    /**
     * @var
     */
    private $orderRepository;

    /**
     * @var
     */
    private $productRepository;

    /**
     * MainController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->orderRepository = app(OrderRepository::class);
        $this->productRepository = app(ProductRepository::class);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $lastOrders = $this->orderRepository->getAllOrders(6);
        $lastProducts = $this->productRepository->getLastProducts(4);

        $countOrders = MainRepository::getCountOrders();
        $countUsers = MainRepository::getCountUsers();
        $countProducts = MainRepository::getCountProducts();
        $countCategories = MainRepository::getCountCategories();

        MetaTag::setTags(['title' => 'Админ панель']);

        return view('blog.admin.main.index',
            compact('countOrders',
                'countUsers',
                'countProducts',
                'countCategories',
                'lastOrders',
                'lastProducts'));
    }
}
