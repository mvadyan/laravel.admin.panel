<?php


namespace App\Repositories\Admin;


use App\Models\Admin\Currency;
use App\Repositories\CoreRepository;
use App\Models\Admin\Currency as Model;

/**
 * Class CurrencyRepository
 * @package App\Repositories\Admin
 */
class CurrencyRepository extends CoreRepository
{

    /**
     * CurrencyRepository constructor.
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
     * @return mixed
     */
    public function getAllCurrency()
    {
        return $this->getConditions()::all();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchBaseCurr()
    {
        $id = Currency::where('base', '1')
            ->get()
            ->first();

        if ($id) {
            $new = Currency::find($id->id);
            $new->base = '0';
            $new->save();
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка. Базовой валюты еще нет'])
                ->withInput();
        }

    }

}
