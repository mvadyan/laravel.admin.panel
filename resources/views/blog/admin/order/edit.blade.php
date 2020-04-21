@extends('layouts.app_admin')

@section('content')
    <section class="content-header">
        <h1>
            Редактировать заказ № {{$order->id}}
            @if(!$order->status)
                <a href="{{route('blog.admin.orders.change', $order->id)}}/?status=1" class="btn btn-success btn-xs">Одобрить</a>
                <a href="" class="btn btn-warning btn-xs redact">Редактировать</a>
            @else
                <a href="{{route('blog.admin.orders.change', $order->id)}}/?status=0" class="btn btn-default btn-xs">Вернуть
                    на доработку</a>
            @endif
            @if(in_array($order->status, [0,1]))
                <a href="" class="btn btn-xs">
                    <form id="delform" method="post" action="{{route('blog.admin.orders.destroy', $order->id)}}"
                          style="float: none">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger btn-xs delete">Удалить</button>
                    </form>
                </a>
            @endif
        </h1>
        @component('blog.admin.components.breadcrumbs')
            @slot('parent') Главная @endslot
            @slot('order') Список заказов @endslot
            @slot('active') Заказ № {{$order->id}}@endslot
        @endcomponent
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <form action="{{route('blog.admin.orders.save', $order->id)}}" method="get">

                                @csrf
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td>Номер заказа</td>
                                        <td>{{$order->id}}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата заказа</td>
                                        <td>{{$order->created_at}}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата изменения</td>
                                        <td>{{$order->upcated_at}}</td>
                                    </tr>
                                    <tr>
                                        <td>Кол-во позиций в заказе</td>
                                        <td>{{$order->order_products_count}}</td>
                                    </tr>
                                    <tr>
                                        <td>Сумма</td>
                                        <td>{{$order->total_sum}}</td>
                                    </tr>
                                    <tr>
                                        <td>Имя зказчика</td>
                                        <td>{{$order->user->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Статус</td>
                                        <td>{{$order->order_status['status']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Коментарий</td>
                                        <td>
                                            <input type="text" value="{{$order->note ? $order->note: ''}}"
                                                   placeholder="{{$order->note ? '': 'комментариев нет'}}"
                                                   name="comment">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <input type="submit" name="submit" class="btn btn-warning" value="Сохранить">
                            </form>
                        </div>
                    </div>
                </div>
                <h3>Детали заказа</h3>
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>
                                    <th>Кол-во</th>
                                    <th>Цена</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $qty = 0 @endphp
                                @foreach($order->orderProducts as $product)
                                    <tr>
                                        <td>{{$product->id}}</td>
                                        <td>{{$product->title}}</td>
                                        <td>{{$product->qty, $qty+=$product->qty}}</td>
                                        <td>{{$product->price}}</td>
                                    </tr>
                                @endforeach

                                <tr class="active">
                                    <td colspan="2">Итого:</td>
                                    <td><b>{{$qty}}</b></td>
                                    <td><b>{{$order->total_sum}} {{$order->currency}}</b></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
