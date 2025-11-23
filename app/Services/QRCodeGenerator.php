<?php

namespace App\Services;

use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeGenerator
{
    public static function generarCodigoPaciente(): string
    {
        return 'NHUB-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6));
    }

    public static function generarImagenQR(string $codigo, int $size = 300): string
    {
        return base64_encode(
            QrCode::format('png')
                ->size($size)
                ->errorCorrection('H')
                ->generate($codigo)
        );
    }

    public static function generarImagenQRSvg(string $codigo, int $size = 300): string
    {
        return QrCode::format('svg')
            ->size($size)
            ->errorCorrection('H')
            ->generate($codigo);
    }
}
