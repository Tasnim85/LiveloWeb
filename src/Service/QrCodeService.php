<?php

namespace App\Service;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;

class QrCodeService
{
    public function generateQrCode(string $data): string
    {
        // Create a new QR code using the constructor
        $qrCode = new QrCode($data);

        // Optionally, you can set more configurations, such as size, encoding, etc.
        // Example: $qrCode->setSize(300);

        $writer = new PngWriter();

        /** @var ResultInterface $result */
        $result = $writer->write($qrCode);

        // Return base64 string for embedding in HTML
        return $result->getDataUri();
    }
}
