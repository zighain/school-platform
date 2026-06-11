@extends('layouts.admin')

@section('content')
<h1>Список студентов и записей</h1>
<table class="table">
    <thead>
        <tr>
            <th>Email</th>
            <th>Имя</th>
            <th>Курс</th>
            <th>Статус</th>
            <th>Сертификат</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->user->email }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->course->name }}</td>
            <td>{{ $order->payment_status }}</td>
            <td>
                @if($order->payment_status == 'success')
                    @if($order->certificate_number)
                        {{ $order->certificate_number }}
                    @else
                        {{-- Форма или кнопка для вызова генерации --}}
                        <form action="{{ route('admin.orders.certificate', $order->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-info">Распечатать</button>
                        </form>
                    @endif
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links() }}
@endsection