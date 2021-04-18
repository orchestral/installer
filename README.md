Orchestra Platform Installer
==============

Orchestra Platform Installation Wizard as an extension.

[![tests](https://github.com/orchestral/installer/workflows/tests/badge.svg?branch=master)](https://github.com/orchestral/installer/actions?query=workflow%3Atests+branch%3Amaster)
[![Latest Stable Version](https://poser.pugx.org/orchestra/installer/v/stable)](https://packagist.org/packages/orchestra/installer)
[![Total Downloads](https://poser.pugx.org/orchestra/installer/downloads)](https://packagist.org/packages/orchestra/installer)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/installer/v/unstable)](https://packagist.org/packages/orchestra/installer)
[![License](https://poser.pugx.org/orchestra/installer/license)](https://packagist.org/packages/orchestra/installer)
[![Coverage Status](https://coveralls.io/repos/github/orchestral/installer/badge.svg?branch=master)](https://coveralls.io/github/orchestral/installer?branch=master)

## Installation

To install through composer by using the following command:

```bash
composer require "orchestra/installer"
```

## Configuration

Add following service providers in `config/app.php`.

```php
'providers' => [

    // ...

    Orchestra\Installation\InstallerServiceProvider::class,
],
```
