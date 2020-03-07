Orchestra Platform Installer
==============

Orchestra Platform Installation Wizard as an extension.

[![Build Status](https://travis-ci.org/orchestral/installer.svg?branch=master)](https://travis-ci.org/orchestral/installer)
[![Latest Stable Version](https://poser.pugx.org/orchestra/installer/v/stable)](https://packagist.org/packages/orchestra/installer)
[![Total Downloads](https://poser.pugx.org/orchestra/installer/downloads)](https://packagist.org/packages/orchestra/installer)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/installer/v/unstable)](https://packagist.org/packages/orchestra/installer)
[![License](https://poser.pugx.org/orchestra/installer/license)](https://packagist.org/packages/orchestra/installer)
[![Coverage Status](https://coveralls.io/repos/github/orchestral/installer/badge.svg?branch=master)](https://coveralls.io/github/orchestral/installer?branch=master)

## Table of Content

* [Installation](#installation)
* [Configuration](#configuration)
* [Changelog](https://github.com/orchestral/installer/releases)

## Installation

To install through composer by using the following command:

    composer require "orchestra/installer"

## Configuration

Add following service providers in `config/app.php`.

```php
'providers' => [

    // ...

    Orchestra\Installation\InstallerServiceProvider::class,
],
```
