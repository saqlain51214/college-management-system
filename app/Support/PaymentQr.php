<?php

namespace App\Support;

/**
 * Builds an EMVCo Merchant-Presented QR payload (the same standard used by
 * Pakistani bank / Raast / 1LINK bill QR codes) and renders it as a PNG data
 * URI for embedding in a fee slip.
 *
 * NOTE ON REAL PAYMENTS: a payment app (Easypaisa / JazzCash / bank app) will
 * only complete a transfer if the merchant identifier inside the QR is a REAL
 * merchant registered with the acquiring bank / Raast. The values here are
 * configurable so a registered merchant ID / IBAN can be dropped in later; with
 * test values the QR is structurally valid and scannable (demonstrates the flow)
 * but production apps will not move money to an unregistered merchant.
 */
class PaymentQr
{
    /**
     * Build the raw EMVCo payload string (including CRC).
     *
     * @param array{
     *   guid?:string, merchant_id?:string, merchant_name?:string,
     *   merchant_city?:string, mcc?:string, amount?:float|string|null,
     *   bill_ref?:string|null
     * } $d
     */
    public static function emvPayload(array $d): string
    {
        $guid        = $d['guid']          ?? 'PK.COM.TEST';
        $merchantId  = $d['merchant_id']   ?? '';
        $name        = self::clean($d['merchant_name'] ?? 'MERCHANT', 25);
        $city        = self::clean($d['merchant_city'] ?? 'ASTORE', 15);
        $mcc         = $d['mcc']           ?? '8299'; // 8299 = educational services
        $amount      = $d['amount']        ?? null;
        $billRef     = isset($d['bill_ref']) ? self::clean((string) $d['bill_ref'], 25) : null;

        // Merchant Account Information template (tag 26): GUID + merchant account.
        $mai = self::tlv('00', $guid);
        if ($merchantId !== '') {
            $mai .= self::tlv('01', self::clean($merchantId, 32));
        }

        $p  = self::tlv('00', '01');                       // payload format indicator
        $p .= self::tlv('01', $amount ? '12' : '11');      // 12 = dynamic (amount), 11 = static
        $p .= self::tlv('26', $mai);                       // merchant account info
        $p .= self::tlv('52', $mcc);                       // merchant category code
        $p .= self::tlv('53', '586');                      // currency PKR
        if ($amount !== null && $amount !== '') {
            $p .= self::tlv('54', number_format((float) $amount, 2, '.', ''));
        }
        $p .= self::tlv('58', 'PK');                       // country
        $p .= self::tlv('59', $name);                      // merchant name
        $p .= self::tlv('60', $city);                      // merchant city
        if ($billRef) {
            $p .= self::tlv('62', self::tlv('01', $billRef)); // additional data: bill number
        }

        // CRC over the payload + "6304"
        $p .= '63' . '04';
        $p .= strtoupper(self::crc16($p));

        return $p;
    }

    /**
     * Render the payment QR as a base64 PNG data URI, or null if unavailable.
     *
     * @param array<string,mixed> $d
     */
    public static function png(array $d, int $size = 320): ?string
    {
        if (! function_exists('imagecreatetruecolor')) {
            return null;
        }

        try {
            $payload = self::emvPayload($d);

            if (! class_exists(\Endroid\QrCode\Builder\Builder::class)) {
                return null;
            }

            // endroid/qr-code v6 — options passed to build() as named args.
            $result = (new \Endroid\QrCode\Builder\Builder())->build(
                writer: new \Endroid\QrCode\Writer\PngWriter(),
                data: $payload,
                size: $size,
                margin: 12,
                errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::Medium,
            );

            return $result->getDataUri();
        } catch (\Throwable) {
            return null;
        }
    }

    /** EMV TLV field: 2-digit id + 2-digit length + value. */
    private static function tlv(string $id, string $value): string
    {
        return $id . str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    private static function clean(string $v, int $max): string
    {
        $v = preg_replace('/[^\x20-\x7E]/', '', $v) ?? '';

        return substr(trim($v), 0, $max);
    }

    /** CRC-16/CCITT-FALSE (poly 0x1021, init 0xFFFF) — EMVCo requirement. */
    private static function crc16(string $data): string
    {
        $crc = 0xFFFF;
        for ($i = 0, $n = strlen($data); $i < $n; $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return str_pad(dechex($crc), 4, '0', STR_PAD_LEFT);
    }
}
