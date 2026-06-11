<?php

namespace App\Models;

use App\Services\CertificateService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'course_id',
        'payment_status',
        'certificate_number'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'course_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Проверка возможности отмены записи
     */
    public function canCancel(): bool
    {
        return in_array($this->payment_status, [self::STATUS_PENDING, self::STATUS_FAILED]);
    }

    /**
     * Генерация и сохранение номера сертификата
     */
    public function issueCertificate(CertificateService $service): string
    {
        // 1. Проверка статуса оплаты
        if ($this->payment_status !== self::STATUS_SUCCESS) {
            throw new Exception("Сертификат доступен только для оплаченных заказов.");
        }

        // 2. Проверка завершения курса (согласно ТЗ)
        // Предполагаем, что в модели Course есть поле end_date
        if ($this->course->end_date > now()) {
            throw new Exception("Курс еще не завершен.");
        }

        // 3. Генерация через сервис и сохранение
        $number = $service->issueCertificate($this);
        $this->certificate_number = $number;
        $this->save();

        return $number;
    }
}