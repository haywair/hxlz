<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 13:52
 */

namespace app\wx\base;


use think\Controller;
use think\Request;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
class Membercard extends Controller
{

    public function buildQRcode($text){
        // Create a basic QR code
        $qrCode = new QrCode($text);
        $qrCode->setSize(300);

        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255]);
        $qrCode->setValidateResult(false);

        // Directly output the QR code
        header('Content-Type: '.$qrCode->getContentType());
        return $qrCode->writeString();
    }
}