<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация администратора</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 360px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 24px;
            color: #1f2937;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #4b5563;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
        }
        .btn-submit:hover {
            background-color: #1d4ed8;
        }

        .is-invalid {
            border-color: #dc2626 !important;
            background-color: #fef2f2;
        }
        .invalid-feedback {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h3 class="form-title">Вход в админ-панель</h3>

        <form action="{{ url('/course-admin/login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" name="password" id="password" 
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Войти</button>
        </form>
    </div>

</body>
</html>