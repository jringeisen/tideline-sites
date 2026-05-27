<?php

namespace App\Models;

use App\Enums\InquirySource;
use Database\Factories\ContactInquiryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property InquirySource $source
 */
class ContactInquiry extends Model
{
    /** @use HasFactory<ContactInquiryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'business_name',
        'website',
        'phone',
        'plan',
        'is_veteran',
        'source',
        'message',
        'ip_address',
        'read_at',
        'is_spam',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'source' => InquirySource::class,
            'is_veteran' => 'boolean',
            'read_at' => 'datetime',
            'is_spam' => 'boolean',
        ];
    }

    /**
     * @param  Builder<ContactInquiry>  $query
     */
    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }

    /**
     * @param  Builder<ContactInquiry>  $query
     */
    public function scopeOfSource(Builder $query, InquirySource|string $source): void
    {
        $query->where('source', $source instanceof InquirySource ? $source->value : $source);
    }

    /**
     * @param  Builder<ContactInquiry>  $query
     */
    public function scopeSpam(Builder $query): void
    {
        $query->where('is_spam', true);
    }

    /**
     * Whether a submission with the given email or IP has previously been
     * marked as spam and should be silently rejected.
     */
    public static function isBlockedSubmission(?string $email, ?string $ip): bool
    {
        if (blank($email) && blank($ip)) {
            return false;
        }

        return static::query()
            ->spam()
            ->where(function (Builder $query) use ($email, $ip): void {
                if (filled($email)) {
                    $query->whereRaw('lower(email) = ?', [strtolower($email)]);
                }

                if (filled($ip)) {
                    $query->orWhere('ip_address', $ip);
                }
            })
            ->exists();
    }
}
