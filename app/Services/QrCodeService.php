<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class QrCodeService
{
    /**
     * Generate or return static QR code image URL for an order code.
     */
    public static function generateForOrder(string $orderCode): string
    {
        $dir = public_path('qrcodes');
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $filePath = "{$dir}/{$orderCode}.png";
        if (File::exists($filePath)) {
            return asset("qrcodes/{$orderCode}.png");
        }

        try {
            $escapedCode = escapeshellarg($orderCode);
            $escapedPath = escapeshellarg($filePath);
            $cmd = "python3 -c \"import qrcode; qr=qrcode.QRCode(box_size=8, border=2); qr.add_data({$escapedCode}); qr.make(fit=True); img=qr.make_image(fill_color='black', back_color='white'); img.save({$escapedPath})\"";
            @exec($cmd);

            if (File::exists($filePath)) {
                return asset("qrcodes/{$orderCode}.png");
            }
        } catch (\Throwable $e) {
            Log::warning('QrCodeService generation failed: '.$e->getMessage());
        }

        return 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data='.urlencode($orderCode);
    }
}
