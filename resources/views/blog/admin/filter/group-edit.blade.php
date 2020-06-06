@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumbs')
            @slot('title') Редактирование группы @endslot
            @slot('parent') Главная @endslot
            @slot('group_filter') Группы фильтров @endslot
            @slot('active') Редактирование группы @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form action="{{url('/admin/filter/group-edit', $group->id)}}" method="post" data-toggle="validator">
                        @csrf

                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="title">Нименование группы</label>
                                <input type="text" name="title" class="form-control" id="title"
                                       placeholder="Нименование группы" value="{{$group->title}}" required>

                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Изменить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
