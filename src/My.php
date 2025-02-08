<?php

declare(strict_types=1);

namespace Dotclear\Plugin\SmtpMailer;

use Dotclear\Module\MyPlugin;

/**
 * @brief       SmtpMailer My helper.
 * @ingroup     SmtpMailer
 *
 * @author      Jean-Christian Paul Denis
 * @copyright   AGPL-3.0
 */
class My extends MyPlugin
{
    protected static function checkCustomContext(int $context): ?bool
    {
        return true;
    }
}
