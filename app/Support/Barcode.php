<?php

namespace App\Support;

use Picqer\Barcode\BarcodeGeneratorPNG;

/**
 * Generates a scannable Code 128 barcode as a base64 PNG data URI, suitable
 * for embedding in dompdf-rendered fee slips (dompdf renders <img> reliably,
 * but cannot render inline SVG barcodes).
 */
class Barcode
{
    /**
     * @param  string  $value  The data to encode (e.g. the challan number).
     * @param  string  $hex    Bar colour as a hex string.
     * @return string|null      data:image/png;base64,... or null if unavailable.
     */
    public static function code128Png(string $value, string $hex = '#000000'): ?string
    {
        $value = trim($value);

        if ($value === '' || ! class_exists(BarcodeGeneratorPNG::class) || ! function_exists('imagecreatetruecolor')) {
            return null;
        }

        try {
            $generator = new BarcodeGeneratorPNG();

            // widthFactor 3 + height 60 keeps bars crisp enough to scan from a phone
            // even after the PDF renderer scales the image down a little.
            $png = $generator->getBarcode(
                $value,
                $generator::TYPE_CODE_128,
                3,
                60,
                self::hexToRgb($hex),
            );

            return 'data:image/png;base64,' . base64_encode($png);
        } catch (\Throwable) {
            return null;
        }
    }

    /** @return array{0:int,1:int,2:int} */
    private static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) !== 6) {
            return [0, 0, 0];
        }

        return [
            (int) hexdec(substr($hex, 0, 2)),
            (int) hexdec(substr($hex, 2, 2)),
            (int) hexdec(substr($hex, 4, 2)),
        ];
    }
}
