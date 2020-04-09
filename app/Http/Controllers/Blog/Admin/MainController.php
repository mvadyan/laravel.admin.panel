<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MetaTag;

/**
 * Class MainController
 * @package App\Http\Controllers\Blog\Admin
 */
class MainController extends AdminBaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        MetaTag::setTags(['title' => 'Админ панель']);
        return view('blog.admin.main.index');
    }
}