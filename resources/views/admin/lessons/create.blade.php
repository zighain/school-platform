@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Добавить урок к курсу: {{ $course->name }}</h2>

    <form action="{{ route('admin.courses.lessons.store', $course->id) }}" method="POST">
        @csrf

        {{-- Заголовок --}}
        <div class="form-group mb-3">
            <label>Заголовок (макс. 50 символов)</label>
            <input type="text" name="name" 
                   class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" maxlength="50" required>
            @error('name')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Содержание --}}
        <div class="form-group mb-3">
            <label>Текстовое содержание</label>
            <textarea name="content" 
                      class="form-control @error('content') is-invalid @enderror" 
                      rows="5" required>{{ old('content') }}</textarea>
            @error('content')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Видеоссылка --}}
        <div class="form-group mb-3">
            <label>Видеоссылка (https://super-tube.cc/video/...)</label>
            <input type="url" name="video_link" 
                   class="form-control @error('video_link') is-invalid @enderror" 
                   value="{{ old('video_link') }}" 
                   placeholder="https://super-tube.cc/video/...">
            @error('video_link')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Длительность --}}
        <div class="form-group mb-3">
            <label>Длительность (целое число, не более 4 часов)</label>
            <input type="number" name="hours" 
                   class="form-control @error('hours') is-invalid @enderror" 
                   value="{{ old('hours') }}" min="1" max="4" required>
            @error('hours')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Сохранить урок</button>
        <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
@endsection