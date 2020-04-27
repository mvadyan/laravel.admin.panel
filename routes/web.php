<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**Admin site*/
Route::group(['middleware' => ['status', 'auth']], function () {
    $groupData = [
        'namespace' => 'Blog\Admin',
        'prefix' => 'admin',
    ];

    Route::group($groupData, function () {
        Route::resource('index', 'MainController')
            ->names('blog.admin.index');

        Route::resource('orders', 'OrderController')
        ->names('blog.admin.orders');
        Route::get('/orders/change/{id}', 'OrderController@change')
        ->name('blog.admin.orders.change');
        Route::get('/orders/save/{id}', 'OrderController@save')
            ->name('blog.admin.orders.save');
        Route::get('/orders/forceDestroy/{id}', 'OrderController@forceDestroy')
            ->name('blog.admin.orders.forceDestroy');

        Route::get('/categories/myDel', 'CategoryController@myDel')
            ->name('blog.admin.categories.myDel');
        Route::resource('/categories', 'CategoryController')
            ->names('blog.admin.categories');



    });
});

Route::get('user/index', 'Blog\User\MainController@index');
