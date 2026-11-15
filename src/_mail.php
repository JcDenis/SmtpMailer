<?php
/**
 * @file
 * @brief       The plugin SmtpMailer _mail function
 * @ingroup     SmtpMailer
 *
 * This file can also be required from Dotclear config.php file,
 * after SMTP_MAILER_* constants, to send by SMTP the mails sent
 * before plugins are loaded (password recovery from admin login page).
 *
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */

use Dotclear\App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if (!function_exists('_mail')
    && defined('SMTP_MAILER_HOST')
    && defined('SMTP_MAILER_USERNAME')
    && defined('SMTP_MAILER_PASSWORD')
    && defined('SMTP_MAILER_FROM')
) {
    // PHPMailer library, see https://github.com/PHPMailer/PHPMailer (without autoloader to work from config.php)
    $smtp_mailer_lib = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    foreach (['Exception', 'PHPMailer', 'SMTP'] as $smtp_mailer_class) {
        if (!class_exists('PHPMailer\\PHPMailer\\' . $smtp_mailer_class, false)) {
            require_once $smtp_mailer_lib . $smtp_mailer_class . '.php';
        }
    }
    unset($smtp_mailer_class);
    unset($smtp_mailer_lib);

    /**
     * Send a mail through SMTP.
     *
     * Throws an exception on failure, as core does when PHP mail() fails,
     * so callers can tell the user.
     *
     * @param   mixed   $headers    The headers, as string or array
     */
    function _mail(string $to, string $subject, string $message, mixed $headers = '', ?string $params = null): bool
    {
        $host     = is_string(SMTP_MAILER_HOST) ? SMTP_MAILER_HOST : '';
        $username = is_string(SMTP_MAILER_USERNAME) ? SMTP_MAILER_USERNAME : '';
        $password = is_string(SMTP_MAILER_PASSWORD) ? SMTP_MAILER_PASSWORD : '';
        $from     = is_string(SMTP_MAILER_FROM) ? SMTP_MAILER_FROM : '';

        // Encryption: SMTP_MAILER_ENCRYPTION = 'starttls', 'smtps' or 'none', else SMTP_MAILER_STARTTLS (true = starttls, false = smtps)
        $encryption = defined('SMTP_MAILER_ENCRYPTION') && is_string(SMTP_MAILER_ENCRYPTION) ? strtolower(SMTP_MAILER_ENCRYPTION) : '';
        if (!in_array($encryption, ['starttls', 'smtps', 'none'], true)) {
            $encryption = defined('SMTP_MAILER_STARTTLS') && SMTP_MAILER_STARTTLS ? 'starttls' : 'smtps';
        }
        $port = defined('SMTP_MAILER_PORT') && is_numeric(SMTP_MAILER_PORT) ? (int) SMTP_MAILER_PORT : match ($encryption) {
            'starttls' => 587,
            'smtps'    => 465,
            default    => 25,
        };
        $auth        = !defined('SMTP_MAILER_AUTH') || SMTP_MAILER_AUTH;
        $verify_peer = !defined('SMTP_MAILER_VERIFY_PEER') || SMTP_MAILER_VERIFY_PEER;

        // Headers: core gives them as a string with one header per line
        if (is_string($headers)) {
            $headers = preg_split('/\r?\n(?![ \t])/', $headers) ?: [];
        } elseif (!is_array($headers)) {
            $headers = [];
        }

        // Parse addresses of a header value
        $addresses = function (string $value): array {
            $res = [];
            foreach (PHPMailer::parseAddresses($value, true, 'UTF-8') as $address) {
                if (is_array($address) && is_string($address['address'] ?? null)) {
                    $res[] = ['address' => $address['address'], 'name' => is_string($address['name'] ?? null) ? $address['name'] : ''];
                }
            }

            return $res;
        };

        $mail          = new PHPMailer(true);
        $mail->CharSet = PHPMailer::CHARSET_UTF8;

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->Port       = $port;
            $mail->SMTPAuth   = $auth;
            $mail->Username   = $username;
            $mail->Password   = $password;
            $mail->SMTPSecure = match ($encryption) {
                'starttls' => PHPMailer::ENCRYPTION_STARTTLS,
                'smtps'    => PHPMailer::ENCRYPTION_SMTPS,
                default    => '',
            };
            $mail->SMTPAutoTLS = $encryption !== 'none';
            if (!$verify_peer) {
                $mail->SMTPOptions = ['ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ]];
            }

            // Headers
            $from_name = class_exists(App::class) ? App::config()->vendorName() : 'Dotclear';
            $reply_to  = false;
            foreach ($headers as $header) {
                if (!is_string($header) || !str_contains($header, ':')) {
                    continue;
                }
                [$name, $value] = array_map(trim(...), explode(':', $header, 2));
                $decoded        = iconv_mime_decode($value, ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8') ?: $value;

                switch (strtolower($name)) {
                    case 'from':
                        // Sender address must stay the SMTP account one, the given one is used to reply
                        foreach ($addresses($decoded) as $address) {
                            if ($address['name'] !== '') {
                                $from_name = $address['name'];
                            }
                            if (!$reply_to && strcasecmp($address['address'], $from) !== 0) {
                                $mail->addReplyTo($address['address'], $address['name']);
                                $reply_to = true;
                            }

                            break;
                        }

                        break;
                    case 'reply-to':
                        foreach ($addresses($decoded) as $address) {
                            $mail->addReplyTo($address['address'], $address['name']);
                            $reply_to = true;
                        }

                        break;
                    case 'content-type':
                        // PHPMailer writes its own MIME headers
                        if (str_contains(strtolower($value), 'text/html')) {
                            $mail->isHTML(true);
                        }

                        break;
                    case 'mime-version':
                    case 'content-transfer-encoding':
                    case 'to':
                    case 'subject':
                        break;
                    default:
                        $mail->addCustomHeader($name, $value);
                }
            }

            // Recipients
            $mail->setFrom($from, $from_name);
            $mail->addAddress($to);

            // Content
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();

            return true;
        } catch (Throwable $e) {
            // Log the failure, without the message content
            if (class_exists(App::class)) {
                try {
                    $cur = App::log()->openLogCursor();
                    $cur->setField('log_table', 'SmtpMailer');
                    $cur->setField('log_msg', sprintf(
                        "Failed to send mail\n-----\n%s\n-----\n To: %s\n Subject: %s\n",
                        $e->getMessage(),
                        $to,
                        $subject
                    ));
                    App::log()->addLog($cur);
                } catch (Throwable) {
                }
            }

            throw new Exception('Unable to send email', 0, $e);
        }
    }
}
