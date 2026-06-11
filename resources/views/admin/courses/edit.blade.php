@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Редактирование курса</h2>

    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Название курса</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $course->name) }}">
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Описание</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description) }}</textarea>
            @error('description') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Продолжительность (часов)</label>
            <input type="number" name="hours" class="form-control @error('hours') is-invalid @enderror" 
                   value="{{ old('hours', $course->hours) }}">
            @error('hours') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Цена</label>
            <input type="text" name="price" class="form-control @error('price') is-invalid @enderror" 
                   value="{{ old('price', $course->price) }}">
            @error('price') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Дата начала (дд-мм-гггг)</label>
            <input type="text" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                   value="{{ old('start_date', $course->start_date->format('d-m-Y')) }}">
            @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Дата окончания (дд-мм-гггг)</label>
            <input type="text" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                   value="{{ old('end_date', $course->end_date->format('d-m-Y')) }}">
            @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Обложка (оставь пустым, если не меняешь)</label>
            @if($course->img)
                <div><img src="{{ asset('storage/' . $course->img) }}" width="100"></div>
            @endif
            <input type="file" name="img" class="form-control @error('img') is-invalid @enderror">
            @error('img') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
    </form>
</div>
@endsection