<?php

namespace App\Http\Controllers\Blog\Admin;

use Illuminate\Http\Request;
use \App\Http\Controllers\Blog\BaseController as MainBaseController;

/**
 * Class AdminBaseController
 * @package App\Http\Controllers\Blog\Admin
 */
abstract class AdminBaseController extends MainBaseController
{
    /**
     * AdminBaseController constructor.
     */
    public function __construct()
    {
        $this->getMiddleware('auth');
        $this->getMiddleware('status');
    }
}
