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

* `src/My.php`: module helpers (`My::id()`, settings, permissions).
* `src/Prepend.php`: loaded on every request: autoload, URL handlers, permission types.
* `src/_mail.php`

## Pitfalls

* None recorded yet: add here the traps met while working on the module.

## Testing

* CI browses the common admin and public pages; extra pages can be listed in `.github/dotclear-test.txt`.
