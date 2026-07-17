<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeSlipTemplate extends Model
{
    protected $fillable = [
        'name',
        'variant',
        'is_active',
        'orientation',
        // College header
        'college_name',
        'college_subtitle',
        'college_short_name',
        'logo_path',
        // Colors
        'primary_color',
        'accent_color',
        'text_color',
        // Bank
        'bank_name',
        'bank_logo_path',
        'bank_account',
        'bank_account_title',
        'bank_branch',
        // Challan refs
        'ref_prefix',
        'bill_prefix',
        // Copies
        'copies',
        // Fee table
        'fee_display_mode',
        'fee_items',
        // Options
        'show_barcode',
        'show_watermark',
        'watermark_text',
        'show_in_words',
        'show_depositor_fields',
        'show_ref_no',
        'show_consumer_no',
        'show_accountant_sig',
        // Content
        'instructions',
        'footer_text',
    ];

    protected $casts = [
        'copies'                => 'array',
        'fee_items'             => 'array',
        'is_active'             => 'boolean',
        'show_barcode'          => 'boolean',
        'show_watermark'        => 'boolean',
        'show_in_words'         => 'boolean',
        'show_depositor_fields' => 'boolean',
        'show_ref_no'           => 'boolean',
        'show_consumer_no'      => 'boolean',
        'show_accountant_sig'   => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        // When setting is_active = true, deactivate all others first
        static::saving(function (self $template) {
            if ($template->is_active && $template->isDirty('is_active')) {
                static::where('id', '!=', $template->id ?? 0)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }
        });
    }

    /**
     * Return the active template, falling back to the first one.
     */
    public static function active(): ?self
    {
        return static::where('is_active', true)->first()
            ?? static::first();
    }

    /**
     * Scope to filter by variant.
     */
    public static function forVariant(string $variant): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('variant', $variant)->get();
    }
}
