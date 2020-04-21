<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Repositories\Admin\MainRepository;
use App\Repositories\Admin\OrderRepository;
use App\Services\Admin\OrderService;
use Illuminate\Http\Request;
use MetaTag;

/**
 * Class OrderController
 * @package App\Http\Controllers\Blog\Admin
 */
class OrderController extends AdminBaseController
{

    /**
     * @var OrderRepository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $orderRepository;

    private $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->orderRepository = app(OrderRepository::class);
        $this->orderService = app(OrderService::class);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $perPage = 10;

        $countOrders = MainRepository::getCountOrders();
        $allOrders = $this->orderRepository->getAllOrders($perPage);

        MetaTag::setTags(['title' => 'Список Заказов']);
        return view('blog.admin.order.index', compact('countOrders', 'allOrders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $order = $this->orderRepository->getOneOrder($id);

        MetaTag::setTags(['title' => "Заказ № " . $order->id]);

        return view('blog.admin.order.edit', compact('order'));
    }

    /**
     * @param Request $request
     * @param $id
     * @throws \Illuminate\Validation\ValidationException
     */
    public function change(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'integer|min:0|max:1',
        ]);

        $order = $this->orderRepository->getId($id);

        $result = $this->orderService->changeOrderStatus($order, $request->input('status'));

        if ($result) {
            return redirect()
                ->route('blog.admin.orders.edit', $id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения']);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, $id)
    {
        $this->validate($request, [
            'comment' => 'min:5|max:200',
        ]);

        $order = $this->orderRepository->getId($id);

        $result = $this->orderService->saveOrderComment($order);

        if ($result) {
            return redirect()
                ->route('blog.admin.orders.edit', $id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения']);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $order = $this->orderRepository->getId($id);
        $oldStatus = $order->status;
        $result = $this->orderService->changeOrderStatus($order, '2');

        if ($result) {
            $resultDestroy = $this->orderService->orderDestroy($result, $id);
            if ($resultDestroy) {
                return redirect()
                    ->route('blog.admin.orders.index')
                    ->with(['success' => 'Запись Удалена']);
            } else {
                $this->orderService->changeOrderStatus($order, $oldStatus);

                return redirect()
                    ->route('blog.admin.orders.index')
                    ->withErrors(['msg' => 'Ошибка Удаления']);
            }
        } else {
            return back()
                ->withErrors(['msg' => 'ошибка изменения статуса']);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function forceDestroy($id)
    {
        $result = $this->orderService->orderDelete($id);

        if ($result) {
            return redirect()
                ->route('blog.admin.orders.index')
                ->with(['success' => 'Успешно Удалено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка Удаления']);
        }
    }
}
