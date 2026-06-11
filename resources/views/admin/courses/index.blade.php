@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Список курсов</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">Создать новый курс</a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Название</th>
                <th>Дата начала</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{ $course->name }}</td>
                <td>{{ \Carbon\Carbon::parse($course->start_date)->format('d-m-Y') }}</td>
                <td>{{ number_format($course->price, 2, '.', '') }}</td>
                <td>
                    <a href="{{ route('admin.courses.lessons.index', $course->id) }}" class="btn btn-sm btn-info text-white">Уроки</a>
                    
                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
                    
                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот курс?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $courses->links() }}
    </div>
</div>
@endsection