# Changelog for 4.x

This changelog references the relevant changes (bug and security fixes) done to `orchestra/installer`.

## 4.3.0

Released: 2020-01-04

### Changes

* Bind `orchestra.platform.memory` as alias to `orchestra.memory` default provider.

## 4.2.0

Released: 2019-12-26

### Added

* Added `orchestra:install` artisan command to make installation using terminal.

### Changes

* Move HTTP related logic to controller instead of handling it under `Orchestra\Installation\Installation`.

## 4.1.1

Released: 2019-12-16

### Changes

* Add missing console command description.

## 4.1.0

Released: 2019-12-15

### Added

* Added `orchestra:configure-email` artisan command to allow reconfigurate e-mail configuration using Laravel default configuration and custom sender information (email and name).

## 4.0.0

Released: 2019-09-14

### Changes 

* Update support for Orchestra Platform v4.x.
