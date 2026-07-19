<?php

namespace App\Support;

use Picqer\Barcode\Renderers\PngRenderer;
use Picqer\Barcode\Types\TypeCode128;

/**
 * Generates a scannable Code 128 barcode as a base64 PNG data URI, suitable
 * for embedding in dompdf-rendered fee slips.
 *
 * IMPORTANT: the PNG is rendered with a solid WHITE background (not the
 * library default of a transparent background). dompdf renders transparent
 * PNG areas as black, which would turn the barcode into an unreadable block.
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

        if ($value === '' || ! class_exists(TypeCode128::class) || ! function_exists('imagecreatetruecolor')) {
            return null;
        }

        try {
            $barcode = (new TypeCode128())->getBarcode($value);

            $renderer = new PngRenderer();
            $renderer->setForegroundColor(self::hexToRgb($hex)); // dark bars
            $renderer->setBackgroundColor([255, 255, 255]);      // solid white — critical for dompdf

            // widthFactor 3 keeps the narrowest bar 3px so it stays crisp/scannable
            // even after the PDF renderer scales the image down.
            $png = $renderer->render($barcode, $barcode->getWidth() * 3, 60);

            // Bake a white "quiet zone" (mandatory blank margin) into the image so
            // scanners can locate the start/stop patterns.
            $png = self::addQuietZone($png, 36);

            return 'data:image/png;base64,' . base64_encode($png);
        } catch (\Throwable) {
            return null;
        }
    }

    /** Add a white left/right quiet zone around the barcode PNG. */
    private static function addQuietZone(string $png, int $quiet): string
    {
        $src = @imagecreatefromstring($png);
        if ($src === false) {
            return $png;
        }

        $w = imagesx($src);
        $h = imagesy($src);
        $dst = imagecreatetruecolor($w + 2 * $quiet, $h);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $w + 2 * $quiet, $h, $white);
        imagecopy($dst, $src, $quiet, 0, 0, 0, $w, $h);

        ob_start();
        imagepng($dst);
        $out = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return $out ?: $png;
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
