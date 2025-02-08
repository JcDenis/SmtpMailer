<?php

declare(strict_types=1);

namespace Dotclear\Plugin\SmtpMailer;

use Autoloader;
use Dotclear\App;
use Dotclear\Core\Process;

/**
 * @brief       SmtpMailer module prepend.
 * @ingroup     SmtpMailer
 *
 * @author      Jean-Christian Paul Denis
 * @copyright   AGPL-3.0
 */
class Prepend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::PREPEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // Json Web Token library v6.11.0, see https://github.com/firebase/php-jwt
        Autoloader::me()->addNamespace('PHPMailer\PHPMailer', implode(DIRECTORY_SEPARATOR, [
            My::path(), 'lib', 'PHPMailer', 'src'
        ]));

        require_once __DIR__ . '/_mail.php';

        return true;
    }
}
