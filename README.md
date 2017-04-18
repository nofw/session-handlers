# Session handlers

[![Latest Version](https://img.shields.io/github/release/nofw/session-handlers.svg?style=flat-square)](https://github.com/nofw/session-handlers/releases)
[![Build Status](https://img.shields.io/travis/nofw/session-handlers.svg?style=flat-square)](https://travis-ci.org/nofw/session-handlers)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/nofw/session-handlers.svg?style=flat-square)](https://scrutinizer-ci.com/g/nofw/session-handlers)
[![Quality Score](https://img.shields.io/scrutinizer/g/nofw/session-handlers.svg?style=flat-square)](https://scrutinizer-ci.com/g/nofw/session-handlers)
[![Total Downloads](https://img.shields.io/packagist/dt/nofw/session-handlers.svg?style=flat-square)](https://packagist.org/packages/nofw/session-handlers)

**Package providing various `SessionHandlerInterface` implementations.**


## Install

Via Composer

``` bash
$ composer require nofw/session-handlers
```


## Usage

This package provides three Session Handler implementations:

- PSR-6
- PSR-16
- Doctrine Cache

Choose your backend and instantiate and register handler.

``` php
$cache = new ImaginaryCacheItemPool();
$handler = new \Nofw\Session\CacheSessionHandler($cache);

session_set_save_handler($handler);
```

Use your session as usual.


### Logging

The [SessionHandlerInterface](http://php.net/manual/en/class.sessionhandlerinterface.php) does not allow throwing exceptions to indicate failure. Instead it expects the handler to return empty values (empty string or false). However, the PSR-X implementations do throw exceptions. To adhere the interface and to not lose the ability to detect failures, these implementations accept a PSR-3 logger as their second constructor argument and also implement the `LoggerAwareInterface`.

``` php
$cache = new ImaginaryCacheItemPool();
$logger = new Monolog\Logger('nofw')
$handler = new \Nofw\Session\CacheSessionHandler($cache, $logger);
```

The caught exceptions are logged as errors.


## Testing

``` bash
$ make test
```


## Security

If you discover any security related issues, please contact us at [mark.sagikazar@gmail.com](mailto:mark.sagikazar@gmail.com).


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
