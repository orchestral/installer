Orchestra Platform Installer
==============

Orchestra Platform Installation Wizard as an extension.

[![Build Status](https://travis-ci.org/orchestral/installer.svg?branch=master)](https://travis-ci.org/orchestral/installer)
[![Latest Stable Version](https://poser.pugx.org/orchestra/installer/v/stable)](https://packagist.org/packages/orchestra/installer)
[![Total Downloads](https://poser.pugx.org/orchestra/installer/downloads)](https://packagist.org/packages/orchestra/installer)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/installer/v/unstable)](https://packagist.org/packages/orchestra/installer)
[![License](https://poser.pugx.org/orchestra/installer/license)](https://packagist.org/packages/orchestra/installer)

## Table of Content

* [Installation](#installation)
* [Configuration](#configuration)
* [Changelog](https://github.com/orchestral/installer/releases)

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

    Orchestra\Installation\InstallerServiceProvider::class,
],
```
