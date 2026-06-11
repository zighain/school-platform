@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Создание курса</h2>
    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Название курса (макс. 30 симв.)</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Описание (макс. 100 симв.)</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Продолжительность (часы)</label>
                <input type="number" name="hours" class="form-control @error('hours') is-invalid @enderror" value="{{ old('hours') }}">
                @error('hours') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label>Цена (хх.хх)</label>
                <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
                @error('price') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Дата начала (дд-мм-гггг)</label>
                <input type="text" name="start_date" class="form-control @error('start_date') is-invalid @enderror" placeholder="01-01-2026" value="{{ old('start_date') }}">
                @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label>Дата окончания (дд-мм-гггг)</label>
                <input type="text" name="end_date" class="form-control @error('end_date') is-invalid @enderror" placeholder="31-12-2026" value="{{ old('end_date') }}">
                @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label>Обложка (JPG/JPEG, макс 2000 Кб)</label>
            <input type="file" name="img" class="form-control @error('img') is-invalid @enderror">
            @error('img') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Создать курс</button>
    </form>
</div>
@endsection