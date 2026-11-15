<?php

declare(strict_types=1);

namespace Dotclear\Plugin\SmtpMailer;

use Dotclear\App;
use Dotclear\Helper\Process\TraitProcess;

/**
 * @brief       SmtpMailer backend class.
 * @ingroup     SmtpMailer
 *
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Backend
{
    use TraitProcess;

    public static function init(): bool
    {
        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        App::behavior()->addBehaviors([
            // Warn super admin on dashboard when configuration is missing
            'adminDashboardMessage' => function (): string {
                if (!App::auth()->isSuperAdmin()) {
                    return '';
                }

                $missing = array_filter(
                    ['SMTP_MAILER_HOST', 'SMTP_MAILER_USERNAME', 'SMTP_MAILER_PASSWORD', 'SMTP_MAILER_FROM'],
                    fn (string $constant): bool => !defined($constant)
                );
                if ($missing !== []) {
                    App::backend()->notices()->warning(sprintf(
                        __('Plugin "SMTP Mailer" is enabled but these constants are missing in config.php, mails are not sent by SMTP: %s'),
                        implode(', ', $missing)
                    ), false);
                } elseif (function_exists('_mail')
                    && realpath((string) (new \ReflectionFunction('_mail'))->getFileName()) !== realpath(__DIR__ . DIRECTORY_SEPARATOR . '_mail.php')
                ) {
                    App::backend()->notices()->warning(__('Plugin "SMTP Mailer" is not used: another _mail() function is already defined.'), false);
                }

                return '';
            },
        ]);

        return true;
    }
}
