# SmtpMailer

Notes for AI assistants working on the Dotclear plugin SmtpMailer.

**Read first** the shared conventions of the author's Dotclear modules:
https://raw.githubusercontent.com/JcDenis/gh2da/master/CONVENTIONS.md
(if a web tool refuses the address, use `curl -s` on it from the shell).
The rules below take precedence for this module.

## Module

Simple SMTP mailer plugin. This plugin is not completed and only fits my needs, use it at your own risk.

No admin page. Nothing on the public side.

Required Dotclear and PHP versions: see `_define.php`.

## Structure

* `src/Backend.php`: admin side: dashboard warning when configuration is missing.
* `src/My.php`: module helpers (`My::id()`, settings, permissions).
* `src/Prepend.php`: loaded on every request: autoload, URL handlers, permission types.
* `src/_mail.php`: the `_mail()` function used by the core instead of PHP `mail()`; it loads PHPMailer itself so it can be required from config.php.
* `lib/PHPMailer/`: PHPMailer library.
* Behaviors used: `adminDashboardMessage`.

## Pitfalls

* The core gives `_mail()` headers as one string (one header per line), not an array.
* The core ignores the result of `_mail()` (branch `fix-mail-user-function-result` on dotclear-private): `_mail()` throws an exception on failure.
* Plugins are not loaded on the admin login page: mails sent there need `_mail.php` required from config.php.
* Never log the message content (it can hold a password recovery link).

## Testing

* CI browses the common admin and public pages; extra pages can be listed in `.github/dotclear-test.txt`.
