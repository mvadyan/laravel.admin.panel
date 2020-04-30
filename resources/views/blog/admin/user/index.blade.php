@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumbs')
            @slot('title') Список Пользователей @endslot
            @slot('parent') Главная @endslot
            @slot('active') Список Пользователей @endslot
        @endcomponent
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Логин</th>
                                    <th>Email</th>
                                    <th>Имя</th>
                                    <th>Роль</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($allUsers as $user)
                                    @php
                                        $class = '';
                                        $status = $user->roles->first()->name;
                                        if ($status == 'disabled') $class = "danger";
                                    @endphp
                                    <tr class="{{$class}}">
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{ucfirst($user->name)}}</td>
                                        <td>{{$user->roles->first()->name}}</td>
                                        <td>
                                            <a href="{{route('blog.admin.users.edit', $user->id)}}"
                                               title="просмотреть пользователя"><i class="btn btn-xs"></i>
                                                <button type="submit" class="btn btn-success btn-xs">
                                                    Просмотреть
                                                </button>
                                            </a>
                                            <a class="btn btn-xs">
                                                <form method="post"
                                                      action="{{route('blog.admin.users.destroy', $user->id)}}"
                                                      style="float: none">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-xs delete">Удалить</button>
                                                </form>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center"><h2>Пользователей нет</h2></td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <p>{{count($allUsers)}} пользователей из {{$countUsers}}</p>

                            @if($allUsers->total() > $allUsers->count())
                                <br>
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                {{$allUsers->links()}}
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
