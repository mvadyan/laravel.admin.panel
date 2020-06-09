@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumbs')
            @slot('title') Валюта @endslot
            @slot('parent') Главная @endslot
            @slot('active') Валюта @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <a href="{{url('/admin/currency/add')}}" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i>Добавить валюту</a>
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>
                                    <th>Код</th>
                                    <th>Значение</th>
                                    <th>Базовая</th>
                                    <th>Действие</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($currency as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->code}}</td>
                                        <td>{{$item->value}}</td>
                                        <td>{{$item->base == 1 ? 'Да': 'Нет'}}</td>
                                        <td>
                                            <a href="{{url('/admin/currency/edit', $item->id)}}" title="редактировать" style="margin: 5px"><i
                                                    class="fa fa-fw fa-edit"></i></a>
                                            <a href="{{url('/admin/currency/delete', $item->id)}}" title="удалить" style="margin: 5px"><i
                                                    class="fa fa-fw fa-close text-danger delete"></i></a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
