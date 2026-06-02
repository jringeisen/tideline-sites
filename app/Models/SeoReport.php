<?php

namespace App\Models;

use App\Enums\SeoReportStatus;
use Database\Factories\SeoReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property SeoReportStatus $status
 * @property array<string, mixed>|null $report
 * @property array<string, mixed>|null $raw_signals
 */
class SeoReport extends Model
{
    /** @use HasFactory<SeoReportFactory> */
    use HasFactory;

    protected $fillable = [
        'token',
        'url',
        'host',
        'industry',
        'status',
        'score',
        'report',
        'raw_signals',
        'email',
        'email_captured_at',
        'contact_inquiry_id',
        'ip_address',
        'error',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => SeoReportStatus::class,
            'report' => 'array',
            'raw_signals' => 'array',
            'email_captured_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SeoReport $report): void {
            if (blank($report->token)) {
                $report->token = (string) Str::ulid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    /**
     * @return BelongsTo<ContactInquiry, $this>
     */
    public function contactInquiry(): BelongsTo
    {
        return $this->belongsTo(ContactInquiry::class);
    }

    /**
     * Whether the guest has unlocked the full report by submitting their email.
     */
    public function isUnlocked(): bool
    {
        return $this->email_captured_at !== null;
    }
}
