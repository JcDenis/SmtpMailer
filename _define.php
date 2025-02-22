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
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/src/branch/master/README.md',
        'repository'  => 'https://github.com/JcDenis/' . $this->id . '/raw/branch/master/dcstore.xml',
        'date'        => '2025-02-10T22:47:16+00:00',
    ]
);
