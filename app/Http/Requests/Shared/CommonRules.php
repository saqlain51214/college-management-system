<?php

namespace App\Http\Requests\Shared;

class CommonRules
{
    // ─── Text ───────────────────────────────────────────────────────────────

    public static function name(bool $required = true): array
    {
        return array_filter([
            $required ? 'required' : 'nullable',
            'string',
            'max:100',
            'regex:/^[\p{L}\s\-\.]+$/u',
        ]);
    }

    public static function urduName(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
            'max:200',
        ];
    }

    public static function description(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
            'max:5000',
        ];
    }

    public static function richText(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
        ];
    }

    // ─── Identifier ─────────────────────────────────────────────────────────

    public static function slug(): array
    {
        return [
            'nullable',
            'string',
            'max:150',
            'regex:/^[a-z0-9\-]+$/',
        ];
    }

    public static function code(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
            'max:20',
            'regex:/^[A-Z0-9\-]+$/',
        ];
    }

    public static function courseCode(): array
    {
        return [
            'required',
            'string',
            'max:15',
            'regex:/^[A-Z]{2,6}-[0-9]{3,4}$/',
        ];
    }

    // ─── Contact ─────────────────────────────────────────────────────────────

    public static function email(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
            'email',
            'max:150',
        ];
    }

    public static function phone(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
            'regex:/^(\+92|0)[0-9]{10}$/',
        ];
    }

    public static function cnic(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'string',
            'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/',
        ];
    }

    // ─── Media ───────────────────────────────────────────────────────────────

    public static function image(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
        ];
    }

    public static function document(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'file',
            'mimes:pdf,doc,docx',
            'max:5120',
        ];
    }

    // ─── Numeric ─────────────────────────────────────────────────────────────

    public static function sortOrder(): array
    {
        return ['nullable', 'integer', 'min:0', 'max:999'];
    }

    public static function creditHours(): array
    {
        return ['required', 'integer', 'min:1', 'max:6'];
    }

    public static function percentage(): array
    {
        return ['nullable', 'numeric', 'min:0', 'max:100'];
    }

    // ─── Status / Boolean ────────────────────────────────────────────────────

    public static function isActive(): array
    {
        return ['sometimes', 'boolean'];
    }

    // ─── Date ────────────────────────────────────────────────────────────────

    public static function date(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'date',
        ];
    }

    public static function year(): array
    {
        return ['nullable', 'integer', 'min:1900', 'max:2100'];
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public static function foreignKey(string $table, bool $required = true): array
    {
        return [
            $required ? 'required' : 'nullable',
            'integer',
            "exists:{$table},id",
        ];
    }

    public static function password(): array
    {
        return [
            'required',
            'string',
            'min:8',
            'confirmed',
        ];
    }
}
