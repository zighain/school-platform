@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Список студентов</h1>

    {{-- Форма фильтрации --}}
    <form method="GET" action="{{ route('admin.students') }}" class="mb-4">
        <div class="row align-items-center">
            <div class="col-md-4">
                <select name="course_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Все курсы</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.students') }}" class="btn btn-secondary">Сбросить</a>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Email</th>
                <th>Имя</th>
                <th>Курс</th>
                <th>Дата записи</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $order)
            <tr>
            <td>{{ $order->user->email ?? '—' }}</td>
                  <td>{{ $order->user->name ?? '—' }}</td>
                  <td>{{ $order->course->name ?? '—' }}</td>
                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                <td>
                    @if($order->payment_status == 'pending') 
                        <span class="badge bg-warning text-dark">Ожидает оплаты</span>
                    @elseif($order->payment_status == 'success') 
                        <span class="badge bg-success">Оплачено</span>
                    @else 
                        <span class="badge bg-danger">Ошибка оплаты</span> 
                    @endif
                </td>
                <td>
                    @if($order->payment_status == 'success')
                        @if($order->certificate_number)
                            <small class="text-muted">№ {{ $order->certificate_number }}</small>
                        @else
                            <a href="{{ route('admin.courses.printCertificate', $order->id) }}" class="btn btn-sm btn-primary">Сертификат</a>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Пагинация --}}
    <div class="mt-3">
        {{ $students->appends(request()->query())->links() }}
    </div>
</div>
@endsection