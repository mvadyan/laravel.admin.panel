<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\AdminCurrencyAddRequest;
use App\Models\Admin\Currency;
use App\Repositories\Admin\CurrencyRepository;
use MetaTag;

/**
 * Class CurrencyController
 * @package App\Http\Controllers\Blog\Admin
 */
class CurrencyController extends AdminBaseController
{
    /**
     * @var CurrencyRepository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $currencyRepository;

    /**
     * CurrencyController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->currencyRepository = app(CurrencyRepository::class);
    }

    public function index()
    {
        $currency = $this->currencyRepository->getAllCurrency();

        MetaTag::setTags(['title' => 'Валюта']);
        return view('blog.admin.currency.index', compact('currency'));
    }

    /**
     * @param AdminCurrencyAddRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function addCurrency(AdminCurrencyAddRequest $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();
            $currency = (new Currency())->create($data);

            if ($request->base == 'on') {
                $this->currencyRepository->switchBaseCurr();
            }

            $currency->base = $request->base ? '1' : '0';
            $currency->save();

            if ($currency) {
                return redirect('/admin/currency/add')
                    ->with(['success' => 'Валюта добавлена']);
            } else {
                return back()
                    ->withErrors(['msg' => 'Ошибка добавления валюты'])
                    ->withInput();
            }
        } else {
            if ($request->isMethod('get')) {
                MetaTag::setTags(['title' => 'Добавление валюты']);
                return view('blog.admin.currency.add');
            }
        }
    }

    public function editCurrency(AdminCurrencyAddRequest $request, $id)
    {
        if (empty($id)) {
            return back()->withErrors(['msg' => "Запись [{$id}] не найдена!"]);
        }

        if ($request->isMethod('post')) {
            $currency = $this->currencyRepository->getId($id);
            $currency->title = $request->title;
            $currency->code = $request->code;
            $currency->symbol_left = $request->symbol_left;
            $currency->symbol_right = $request->symbol_right;
            $currency->value = $request->value;
            $currency->base = $request->base ? '1' : '0';
            $currency->save();

            if ($currency) {
                return redirect(url('/admin/currency/edit', $id))
                    ->with(['success' => 'Сохранено']);
            } else {
                return back()
                    ->withErrors(['msg' => 'Ошибка редактирования валюты'])
                    ->withInput();
            }
        } else {
            if ($request->isMethod('get')) {
                $currency = $this->currencyRepository->getId($id);

                MetaTag::setTags(['title' => 'Редактирование валюты']);
                return view('blog.admin.currency.edit', compact('currency'));
            }
        }
    }

    /**
     * @param $id
     */
    public function deleteCurrency($id)
    {
        if (empty($id)) {
            return back()->withErrors(['msg' => "Запись [{$id}] не найдена!"]);
        }

        $delete = $this->currencyRepository->getId($id)->forceDelete();

        if ($delete) {
            return redirect('/admin/currency/index')
                ->with(['success' => 'Удалено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка удаления']);
        }
    }


}
