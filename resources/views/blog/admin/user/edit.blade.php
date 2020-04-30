@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumbs')
            @slot('title') Редактирование пользователя @endslot
            @slot('parent') Главная @endslot
            @slot('user') Список Пользователей @endslot
            @slot('active') Редактирование пользователя @endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form action="{{route('blog.admin.users.update', $user->id)}}" method="post"
                          data-toggle="validator">
                        @method('PUT')
                        @csrf
                        <div class="box-body">
                            <div class="form-group has-feedback">
                                <label for="login">Логин <small style="font-size: small">меняется автоматически</small></label>
                                <input type="text" class="form-control" placeholder="{{ucfirst($user->name)}}" disabled>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Пароль</label>
                                <input type="text" class="form-control" name="password"
                                       placeholder="введите пароль, если хотите его изменить">
                            </div>
                            <div class="form-group">
                                <label for="">Подтверждение пароля</label>
                                <input type="text" class="form-control" name="password_confirmation"
                                       placeholder="Подтверждение пароля">
                            </div>
                            <div class="form-group has-feedback">
                                <label for="name">Имя</label>
                                <input type="text" class="form-control" name="name" id="name"
                                       value="@if(old('name')) {{old('name')}} @else {{$user->name ?? ""}} @endif"
                                       required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email"
                                       value="@if(old('email')) {{old('email')}} @else {{$user->email ?? ""}} @endif"
                                       required>
                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="address">Роль</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="2" {{$role->name == 'user' ? 'selected' : ''}}>Пользователь
                                    </option>
                                    <option value="3" {{$role->name == 'admin' ? 'selected' : ''}}>Администратор
                                    </option>
                                    <option value="1" {{$role->name == 'disabled' ? 'selected' : ''}}>Disabled
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>
                </div>
                <h3>Заказы пользователя</h3>
                <div class="box">
                    <div class="box-body">
                        @if(count($orders))
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Статус</th>
                                        <th>Сумма</th>
                                        <th>Дата Создания</th>
                                        <th>Дата Изменения</th>
                                        <th>Действия</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr class="{{$order->order_status['class']}}">
                                            <td>{{$order->id}}</td>
                                            <td>{{$order->order_status['status']}}</td>
                                            <td>{{$order->total_sum}}  {{$order->currency}}</td>
                                            <td>{{$order->created_at}}</td>
                                            <td>{{$order->updated_at}}</td>
                                            <td><a href="{{route('blog.admin.orders.edit', $order->id)}}"><i
                                                        class="fa fa-fw fa-eye"></i></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-danger">Пользователь пока ничего не заказывал...</p>
                        @endif
                    </div>
                </div>
                <div class="text-center">
                    <p>{{$orders->count()}} заказа из {{$orders->total()}}</p>
                    @if($orders->total() > $orders->count())
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        {{$orders->links()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
