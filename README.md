# README

[![Release](https://img.shields.io/github/v/release/jcdenis/SmtpMailer?color=lightblue)](https://github.com/JcDenis/SmtpMailer/releases)
![Date](https://img.shields.io/github/release-date/jcdenis/SmtpMailer?color=red)
[![Dotclear](https://img.shields.io/badge/dotclear-v2.36-137bbb.svg)](https://fr.dotclear.org/download)
[![Dotaddict](https://img.shields.io/badge/dotaddict-official-9ac123.svg)](https://plugins.dotaddict.org/dc2/details/SmtpMailer)
[![License](https://img.shields.io/github/license/jcdenis/SmtpMailer?color=white)](https://github.com/JcDenis/SmtpMailer/blob/master/LICENSE)

## ABOUT

_SmtpMailer_ is a plugin for the open-source web publishing software called [Dotclear](https://www.dotclear.org).

> Simple SMTP mailer plugin.

This plugin is not completed and only fits my needs, use it at your own risk.

## REQUIREMENTS

* Dotclear 2.36
* PHP 8.1+
* Access to config.php file.

## USAGE

First install _SmtpMailer_, manualy from a zip package or from 
Dotaddict repository. (See Dotclear's documentation to know how do this)

This lines must be added to Dotclear _config.php_ file:

	define('SMTP_MAILER_HOST', 'your.mail.hoster.net');
	define('SMTP_MAILER_USERNAME', 'abcdef');
	define('SMTP_MAILER_PASSWORD', 'abcdef');
	define('SMTP_MAILER_FROM', 'xyz@xyz.xyz');
	define('SMTP_MAILER_STARTTLS', true);

## LINKS

* [License](https://github.com/JcDenis/SmtpMailer/blob/master/LICENSE)
* [Packages & details](https://github.com/JcDenis/SmtpMailer/releases) (or on [Dotaddict](https://plugins.dotaddict.org/dc2/details/SmtpMailer))
* [Sources & contributions](https://github.com/JcDenis/SmtpMailer)
* [Issues & security](https://github.com/JcDenis/SmtpMailer/issues)

## CONTRIBUTORS

* Jean-Christian Denis (author)

You are welcome to contribute to this code.

## LICENCE

* This plugin uses [PHPMailer](https://github.com/PHPMailer/PHPMailer) under LGPL2.1 licence.
