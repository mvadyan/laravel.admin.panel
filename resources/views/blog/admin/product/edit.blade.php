@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumbs')
            @slot('title') Редактирование продукта @endslot
            @slot('parent') Главная @endslot
            @slot('product') Список продуктов @endslot
            @slot('active') Редактирование продукта {{$product->title}} @endslot
        @endcomponent
    </section>
    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form method="POST" action="{{route('blog.admin.products.update', $product->id)}}"
                          data-toggle="validator" id="add">
                        @method('PATCH')
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Наименование товара</label>
                                <input type="text" name="title" class="form-control" id="title"
                                       placeholder="Наименование товара" value="{{$product->title}}" required>
                                <span class="glyphicon form-control-feedback"></span>
                            </div>

                            <div class="form-group">
                                <label for="title">Категория товара</label>
                                <select name="parent_id" id="parent_id" class="form-control" required>
                                    <option disabled>-- Выберете категорию --</option>
                                    @include('blog.admin.product.include.categories_for_prod',
['categories' => $categories])
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="keywords">Ключевые слова</label>
                                <input class="form-control" type="text" name="keywords" id="keywords"
                                       placeholder="Ключевые слова" value="{{$product->keywords}}">
                            </div>

                            <div class="form-group">
                                <label for="description">Описание</label>
                                <input class="form-control" type="text" name="description" id="description"
                                       placeholder="Описание" value="{{$product->description}}">
                            </div>
                            <div class="form-group has-feedback">
                                <label for="price">Цена</label>
                                <input class="form-control" type="text" name="price" id="price"
                                       placeholder="Цена" value="{{$product->price}}"
                                       pattern="^[0-9.]{1,}$" required
                                       data-error="Допускаются цифры и десятичная точка">
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="old_price">Старая цена</label>
                                <input class="form-control" type="text" name="old_price" id="old_price"
                                       placeholder="Старая цена" value="{{$product->old_price}}"
                                       pattern="^[0-9.]{1,}$" required
                                       data-error="Допускаются цифры и десятичная точка">
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="content">Контент</label>
                                <textarea name="content" id="editor1" cols="80" rows="10">
                                    {{$product->content}}
                                </textarea>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="status" {{$product->status? 'checked': ''}}> Статус
                                </label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="hit" {{$product->hit? 'checked': ''}}> Хит
                                </label>
                            </div>

                            <div class="form-group has-feedback">
                                <label for="related">Связанные товары</label>
                                <p><small>Начните вводить наименование товара...</small></p>
                                <select name="related[]" class="select2 form-control" id="related" multiple>
                                    @if (!empty($relatedProducts))
                                        @foreach($relatedProducts as $prod)
                                            <option value="{{$prod->id}}" selected>
                                                {{$prod->title}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Фильтры продукта</label>
                                {{Widget::run('filter', ['tpl'=>'widgets.filter', 'filter'=>$filter,])}}
                            </div>

                            <div class="form-group">
                                <div class="col-md-4">
                                    @include('blog.admin.product.include.image_single_edit')
                                </div>
                                <div class="col-md-8">
                                    @include('blog.admin.product.include.image_gallery_edit')
                                </div>
                            </div>

                        </div>
                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="hidden" data-name="{{$id}}"></div>
@endsection
