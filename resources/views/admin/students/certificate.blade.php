<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; text-align: center; padding: 50px; }
        .certificate { border: 10px solid #333; padding: 40px; width: 800px; margin: auto; }
        .number { font-weight: bold; font-size: 20px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>Сертификат об окончании курса</h1>
        <p>Настоящим подтверждается, что</p>
        <h2>{{ $order->user->name }}</h2>
        <p>успешно завершил курс:</p>
        <h3>{{ $order->course->name }}</h3>
        
        <div class="number">
            Номер сертификата: {{ $order->certificate_number }}
        </div>
        <p>Дата выдачи: {{ date('d.m.Y') }}</p>
    </div>
    <script>window.print();</script>
</body>
</html>