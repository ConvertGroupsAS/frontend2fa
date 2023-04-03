<?php

namespace Elgentos\Frontend2FA\Model;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class GoogleAuthenticatorService extends \Neyamtux\Authenticator\Lib\PHPGangsta\GoogleAuthenticator
{
    /**
     * Get QR Code Image.
     *
     * @param string      $name
     * @param string      $secret
     * @param int         $size
     * @param string|null $issuer
     *
     * @return string
     */
    public function getQrCodeEndroid($name, $secret, $title = null, $params = [])
    {
        $size = !empty($params['size']) && (int) $params['size'] > 0 ? (int) $params['size'] : 200;
        $level = !empty($params['level']) && array_search($params['level'], ['L', 'M', 'Q', 'H']) !== false ? $params['level'] : 'M';

        $text = sprintf('otpauth://totp/%s?secret=%s', $name, $secret);
        if (true === is_string($title)) {
            $text = sprintf('%s&issuer=%s', $text, $title);
        }
        $qrCode = new QrCode($text);
        $qrCode->setSize($size);
        $qrCode->setMargin(0);
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());
        $qrCode->setForegroundColor(new Color(0, 0, 0, 0));
        $qrCode->setBackgroundColor(new Color(255, 255, 255, 0));
        $qrCode->setEncoding(new Encoding('UTF-8'));

        $writer = new PngWriter();
        $pngData = $writer->write($qrCode);
        // @codingStandardsIgnoreEnd

        return $pngData->getString();
    }
}
