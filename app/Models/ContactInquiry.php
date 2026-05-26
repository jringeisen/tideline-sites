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
        'read_at',
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
}
