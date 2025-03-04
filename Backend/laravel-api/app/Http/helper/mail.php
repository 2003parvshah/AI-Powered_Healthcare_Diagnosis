<?php

namespace App\Http\helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// require '../vendor/autoload.php';
require __DIR__ . '/../../../vendor/autoload.php';

class mail
{
    public static function sendmail($to, $subject, $message, $attachmentPath = null)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->SMTPAuth = true;
            $mail->Username = config('mail.mailers.smtp.username');
            $mail->Password = config('mail.mailers.smtp.password');
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption') ?? 'ssl';
            $mail->Port = config('mail.mailers.smtp.port');
            $mail->setFrom(config('mail.from.address'), config('mail.from.name'));
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Attachments
            if ($attachmentPath !== null) {
                $mail->addAttachment($attachmentPath);
            }

            return $mail->send() ? "Success" : "Failed";
        } catch (Exception $e) {
            return "Mail Error: " . $mail->ErrorInfo;
        }
    }
}
