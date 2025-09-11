<?php
/**
 * @file
 * @brief       The plugin SmtpMailer _mail function
 * @ingroup     SmtpMailer
 *
 * @author      Jean-Christian Paul Denis
 * @copyright   AGPL-3.0
 */

use Dotclear\App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('\_mail') 
    && defined('SMTP_MAILER_HOST') 
    && defined('SMTP_MAILER_USERNAME') 
    && defined('SMTP_MAILER_PASSWORD') 
    && defined('SMTP_MAILER_FROM')
) {
    function _mail(string $to, string $subject, string $message, $headers = '', ?string $params = null): bool
    {
        if (!App::blog()->isDefined()) {
            return false;
        }

        // Use PHPMailer 6.9.3
        $mail = new PHPMailer(true);
        $mail->CharSet = PHPMailer::CHARSET_UTF8;

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; //App::config()->debugMode() ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = SMTP_MAILER_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_MAILER_USERNAME;
            $mail->Password   = SMTP_MAILER_PASSWORD;
            $mail->SMTPSecure = defined('SMTP_MAILER_STARTTLS') && SMTP_MAILER_STARTTLS ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = defined('SMTP_MAILER_STARTTLS') && SMTP_MAILER_STARTTLS ? 587 : 465;

            //Headers
            if (!empty($headers)) {
                if (!is_array($headers)) {
                    $headers = [$headers];
                }
                foreach($headers as $header) {
                    // @todo: move header "From:" to $mail->setFrom()
                    if (str_starts_with($header, 'From:')
                    // || str_starts_with($header, 'Content-type:')
                    ) {
                        continue;
                    }
                    $mail->addCustomHeader($header);
                }
            }

            //Recipients
            $mail->setFrom(SMTP_MAILER_FROM, App::config()->vendorName());
            $mail->addAddress($to);
            $mail->addReplyTo(SMTP_MAILER_FROM, 'no reply');

            //Content
            //$mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
            //$mail->AltBody = $message;

            $mail->send();

            return true;
        } catch (Throwable $e) {
            $cur = App::log()->openLogCursor();
            $cur->setField('log_table', 'SmtpMailer');
            $cur->setField('log_msg', 
                "\n-----\n Failed to send mail\n-----\n" . $e->getMessage() . "\n\n" .
                sprintf(
                "%s\n-----\n To: %s\n Subject: %s\n-----\n Message:\n%s\n",
                is_array($headers) ? implode("\n\t", $headers) : (is_string($headers) ? $headers : ''),
                $to,
                $subject,
                $message
            ));
            App::log()->addLog($cur);

            return false;
        }
    }
}
