@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Уроки курса: {{ $course->name }}</h1>
    
    @if(session('errors'))
        <div class="alert alert-danger">{{ session('errors')->first() }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Назад к курсам</a>
        {{-- Кнопка доступна только если уроков меньше 5 --}}
        @if($course->lessons()->count() < 5)
            <a href="{{ route('admin.courses.lessons.create', $course->id) }}" class="btn btn-primary">Добавить урок</a>
        @endif
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Заголовок</th>
                <th>Длительность</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lessons as $lesson)
            <tr>
                <td>{{ $lesson->name }}</td>
                <td>{{ $lesson->hours }} ч.</td>
                <td>
                    <form action="{{ route('admin.courses.lessons.destroy', [$course->id, $lesson->id]) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Удалить?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $lessons->links() }}
</div>
@endsection