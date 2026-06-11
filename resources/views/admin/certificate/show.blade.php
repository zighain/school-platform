<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сертификат: {{ $order->user->name }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; display: flex; justify-content: center; padding-top: 50px; background: #f3f4f6; }
        .certificate {
            width: 800px; padding: 60px; background: white;
            border: 10px solid #2563eb; box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center; position: relative;
        }
        h1 { color: #1f2937; text-transform: uppercase; letter-spacing: 2px; }
        .name { font-size: 24px; margin: 30px 0; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; }
        .cert-number { font-size: 14px; color: #6b7280; margin-top: 40px; }
        
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
        }
        .btn-print {
            padding: 12px 24px; background: #2563eb; color: white;
            border: none; border-radius: 6px; cursor: pointer; font-size: 16px;
        }
    </style>
</head>
<body>

<div class="certificate">
    <h1>Сертификат об окончании курса</h1>
    <p>Настоящим подтверждается, что</p>
    <div class="name"><strong>{{ $order->user->name }}</strong></div>
    <p>успешно завершил(а) обучение по программе:</p>
    <h3>{{ $order->course->name }}</h3>
    
    <div class="cert-number">Номер сертификата: {{ $certNumber }}</div>
    
    <br><br>
    <button class="btn-print no-print" onclick="window.print()">Распечатать сертификат</button>
    <br><br>
    <a href="{{ route('admin.students') }}" class="no-print">Назад к списку</a>
</div>

</body>
</html>