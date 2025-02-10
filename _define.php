<?php
/**
 * @file
 * @brief       The plugin SmtpMailer definition
 * @ingroup     SmtpMailer
 *
 * @defgroup    SmtpMailer Plugin SmtpMailer.
 *
 * SMTP mailer.
 *
 * @author      Jean-Christian Paul Denis
 * @copyright   AGPL-3.0
 */
declare(strict_types=1);

$this->registerModule(
    'SMTP Mailer',
    'SMTP Mailer',
    'Jean-Christian Paul Denis and Contributors',
    '0.2',
    [
        'requires'    => [['core', '2.33']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://git.dotclear.watch/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://git.dotclear.watch/JcDenis/' . $this->id . '/src/branch/master/README.md',
        'repository'  => 'https://git.dotclear.watch/JcDenis/' . $this->id . '/raw/branch/master/dcstore.xml',
        'date'        => '2025-02-08T20:13:34+00:00',
    ]
);
