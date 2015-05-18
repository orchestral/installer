Orchestra Platform Installer
==============

[![Latest Stable Version](https://img.shields.io/github/release/orchestral/installer.svg?style=flat)](https://packagist.org/packages/orchestra/installer)
[![Total Downloads](https://img.shields.io/packagist/dt/orchestra/installer.svg?style=flat)](https://packagist.org/packages/orchestra/installer)
[![MIT License](https://img.shields.io/packagist/l/orchestra/installer.svg?style=flat)](https://packagist.org/packages/orchestra/installer)
[![Build Status](https://img.shields.io/travis/orchestral/installer/3.1.svg?style=flat)](https://travis-ci.org/orchestral/installer)
[![Coverage Status](https://img.shields.io/coveralls/orchestral/installer/3.1.svg?style=flat)](https://coveralls.io/r/orchestral/installer?branch=3.1)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/orchestral/installer/3.1.svg?style=flat)](https://scrutinizer-ci.com/g/orchestral/installer/)

## Table of Content

* [Installation](#installation)
* [Configuration](#configuration)

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "orchestra/installer": "~3.0"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

    composer require "orchestra/installer=~3.0"

## Configuration

Add following service providers in `resources/config/app.php`.

```php
'providers' => [

    // ...

    'Orchestra\Installation\InstallerServiceProvider',
],
```
