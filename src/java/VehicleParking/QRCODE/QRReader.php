<?php
require 'vendor/autoload.php'; // Load ZXing library for QR code reading

use Zxing\QrReader;

class QRCodeReader { // Changed class name to avoid conflict
    /**
     * Reads the QR code from an image file and returns the extracted text.
     * 
     * @param string $path The path to the QR code image file.
     * @return string|false The decoded QR content or false if failed.
     */
    public static function readQR($path) {
        try {
            // Ensure file exists
            if (!file_exists($path)) {
                throw new Exception("File not found: " . $path);
            }

            // Ensure it's a valid image file
            if (!@getimagesize($path)) {
                throw new Exception("Invalid image file: " . $path);
            }

            // Read the QR Code
            $qrCode = new QrReader($path);
            $text = $qrCode->text();

            if (!$text) {
                throw new Exception("QR code could not be read.");
            }

            return $text;
        } catch (Exception $e) {
            error_log("QR Reader Error: " . $e->getMessage());
            return "Error: " . $e->getMessage(); // Return error message for debugging
        }
    }
}

// Example Usage
//$qrContent = QRCodeReader::readQR('qrcodes/sample.png');
//echo $qrContent;
?>
