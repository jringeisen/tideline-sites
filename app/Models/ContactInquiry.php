<?php

namespace App\Models;

use Database\Factories\ContactInquiryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    /** @use HasFactory<ContactInquiryFactory> */
    use HasFactory;

    public const SOURCE_CONTACT = 'contact';

    public const SOURCE_SEO_ASSESSMENT = 'seo_assessment';

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
    public function scopeOfSource(Builder $query, string $source): void
    {
        $query->where('source', $source);
    }
}
