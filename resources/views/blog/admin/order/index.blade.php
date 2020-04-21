@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        @component('blog.admin.components.breadcrumbs')
            @slot('title') Панель Управления @endslot
            @slot('parent') Главная @endslot
            @slot('active') Список Заказов @endslot
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
                                    <th>Покупатель</th>
                                    <th>Статус</th>
                                    <th>Сумма</th>
                                    <th>Дата создания</th>
                                    <th>Дата изменения</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($allOrders as $order)
                                <tr class="{{$order->order_status['class']}}">
                                    <td>{{$order->id}}</td>
                                    <td>{{$order->user->name}}</td>
                                    <td >{{$order->order_status['status']}}</td>
                                    <td>{{$order->total_sum}}</td>
                                    <td>{{$order->created_at}}</td>
                                    <td>{{$order->updated_at}}</td>
                                    <td>
                                        <a href="{{route('blog.admin.orders.edit', $order->id)}}" title="редактировать заказ"><i class="fa fa-fw fa-eye"></i></a>

                                        <a href="{{route('blog.admin.orders.forceDestroy', $order->id)}}" title="Удалить из БД"><i class="fa fa-fw fa-close text-danger" ></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="3"><h2>Заказов нет</h2></td>
                                </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <p>{{$allOrders->count()}}  заказа(ов) из {{$allOrders->total()}}</p>
                            @if($allOrders->total() > $allOrders->count())
                                <br>
                                <div class="row justify-content-center">
                                    <div class="col-md-12">
                                        <div class="card-body">
                                            {{$allOrders->links()}}
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
