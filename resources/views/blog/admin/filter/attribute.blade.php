@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumbs')
            @slot('title') Фильтры @endslot
            @slot('parent') Главная @endslot
            @slot('active') Фильтры @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <a href="{{url('/admin/filter/attrs-add')}}" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i>Добавить атрибут</a>
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>
                                    <th>Группа</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($attrs as $attr)
                                    <tr>
                                        <td>{{$attr->id}}</td>
                                        <td>{{$attr->value}}</td>
                                        <td>{{$attr->filter->title}}</td>
                                        <td>
                                            <a href="{{url('/admin/filter/attr-edit', $attr->id)}}" title="Редактирование"><i class="fa fa-fw fa-pencil" style="margin: 5px"></i></a>
                                            <a href="{{url('/admin/filter/attr-delete', $attr->id)}}" title="Удаление" class="delete text-danger"><i class="fa fa-fw fa-close text-danger" style="margin: 5px"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <p>{{$attrs->count()}}  фильтров из  {{$attrs->total()}}</p>
                            @if($attrs->total() > $attrs->count())
                                <br>
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                {{$attrs->links()}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
