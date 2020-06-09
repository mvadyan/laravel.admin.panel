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

        Route::resource('users', 'UserController')
            ->names('blog.admin.users');

        Route::get('/products/related', 'ProductController@related');

        Route::match(['get', 'post'], '/products/ajax-image-upload', 'ProductController@ajaxImage');
        Route::delete('/products/ajax-remove-image/{filename}', 'ProductController@deleteImage');

        Route::post('/products/gallery', 'ProductController@gallery')
            ->name('blog.admin.products.gallery');
        Route::post('/products/delete-gallery', 'ProductController@deleteGallery')
            ->name('blog.admin.products.deleteGallery');

        Route::get('products/return-status/{id}', 'ProductController@returnStatus')
            ->name('blog.admin.products.returnStatus');
        Route::get('products/delete-status/{id}', 'ProductController@deleteStatus')
            ->name('blog.admin.products.deleteStatus');
        Route::get('products/delete-product/{id}', 'ProductController@deleteProduct')
            ->name('blog.admin.products.deleteProduct');

        Route::get('filter/group-filter', 'FilterController@attributeGroup');
        Route::match(['get','post'], '/filter/group-add-group', 'FilterController@groupAdd');
        Route::match(['get','post'], '/filter/group-edit/{id}', 'FilterController@groupEdit');
        Route::get('/filter/group-delete/{id}', 'FilterController@groupDelete');
        Route::get('filter/attributes-filter', 'FilterController@attributeFilter');
        Route::match(['get','post'], '/filter/attrs-add', 'FilterController@attributeAdd');
        Route::match(['get','post'], '/filter/attr-edit/{id}', 'FilterController@attrEdit');
        Route::get('/filter/attr-delete/{id}', 'FilterController@attrDelete');

        Route::get('/currency/index', 'CurrencyController@index');
        Route::match(['get','post'], '/currency/add', 'CurrencyController@addCurrency');
        Route::match(['get','post'], '/currency/edit/{id}', 'CurrencyController@editCurrency');
        Route::get('/currency/delete/{id}', 'CurrencyController@deleteCurrency');


        Route::resource('products', 'ProductController')
            ->names('blog.admin.products');

    });
});

Route::get('user/index', 'Blog\User\MainController@index');
