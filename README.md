# nif-validator

[![Build Status](https://api.travis-ci.org/ulabox/nif-validator.png?branch=master)](http://travis-ci.org/ulabox/nif-validator)

A modern PHP 7.0+ Spanish NIF (Número de Indentifación Fiscal) validator.

## Why another NIF validator? 

Other NIF validators we saw either had really obscure code or just implemented a validator for the DNI/NIE, not the CIF. 

## Installation

Using Composer:

```
composer require ulabox/nif-validator
```

## Usage


```php
<?php

use NifValidator\NifValidator;

//CIF
assert(NifValidator::isValid('B65410011'));
//DNI
assert(NifValidator::isValid('93471790C'));
//NIE
assert(NifValidator::isValid('X5102754C'));

```

Starting from version 1.1.x you can also separetely validate personal and entity nifs

```php
<?php

use NifValidator\NifValidator;

//CIF
assert(NifValidator::isValidEntity('B65410011'));
//DNI
assert(NifValidator::isValidPersonal('93471790C'));
//NIE
assert(NifValidator::isValidPersonal('X5102754C'));

```

This validator does not strip or uppercase any character, it's your responsibility to previously filter the input.
