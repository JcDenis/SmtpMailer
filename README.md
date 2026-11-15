# README

[![Release](https://img.shields.io/github/v/release/jcdenis/SmtpMailer?color=lightblue)](https://github.com/JcDenis/SmtpMailer/releases)
![Date](https://img.shields.io/github/release-date/jcdenis/SmtpMailer?color=red)
[![Dotclear](https://img.shields.io/badge/dotclear-v2.36-137bbb.svg)](https://fr.dotclear.org/download)
[![Repository](https://img.shields.io/badge/repository-official-9ac123.svg)](https://dotclear.org/plugin/detail/SmtpMailer)
[![License](https://img.shields.io/github/license/jcdenis/SmtpMailer?color=white)](https://github.com/JcDenis/SmtpMailer/blob/master/LICENSE)

## ABOUT

_SmtpMailer_ is a plugin for the open-source web publishing software called [Dotclear](https://www.dotclear.org).

> Simple SMTP mailer plugin.

This plugin is not completed and only fits my needs, use it at your own risk.

## REQUIREMENTS

* Dotclear 2.39
* PHP 8.1+
* Access to config.php file.

## USAGE

First install _SmtpMailer_, manually from a zip package or from 
Dotclear plugins repository. (See Dotclear's documentation to know how do this)

These lines must be added to Dotclear _config.php_ file:

	define('SMTP_MAILER_HOST', 'your.mail.hoster.net');
	define('SMTP_MAILER_USERNAME', 'abcdef');
	define('SMTP_MAILER_PASSWORD', 'abcdef');
	define('SMTP_MAILER_FROM', 'xyz@xyz.xyz');
	define('SMTP_MAILER_STARTTLS', true);

Optional lines:

	define('SMTP_MAILER_ENCRYPTION', 'starttls'); // 'starttls', 'smtps' or 'none', replaces SMTP_MAILER_STARTTLS
	define('SMTP_MAILER_PORT', 587);              // default: 587 with starttls, 465 with smtps, 25 without encryption
	define('SMTP_MAILER_AUTH', true);             // false to send without authentication
	define('SMTP_MAILER_VERIFY_PEER', true);      // false to accept a self-signed server certificate

A warning is shown on the dashboard to super admins when a required constant is missing.

Mails are always sent from SMTP_MAILER_FROM address. When a mail gives another
"From:" address, it is used as "Reply-To:" address.

A failed mail throws an error, so the page that sent it can tell it,
and it is recorded in Dotclear logs (without the message content).

Plugins are not loaded on the administration login page. To also send by SMTP
the mails sent from it (password recovery), add this line to _config.php_,
after the lines above:

	require_once __DIR__ . '/../plugins/SmtpMailer/src/_mail.php';

## LINKS

* [License](https://github.com/JcDenis/SmtpMailer/blob/master/LICENSE)
* [Packages & details](https://github.com/JcDenis/SmtpMailer/releases) (or on [Repository](https://dotclear.org/plugin/detail/SmtpMailer))
* [Sources & contributions](https://github.com/JcDenis/SmtpMailer)
* [Issues & security](https://github.com/JcDenis/SmtpMailer/issues)

## CONTRIBUTORS

* Jean-Christian Denis (author)
* Claude (AI assistant by Anthropic)

You are welcome to contribute to this code.

## LICENCE

* This plugin uses [PHPMailer](https://github.com/PHPMailer/PHPMailer) under LGPL2.1 licence.
