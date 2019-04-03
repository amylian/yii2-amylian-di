amylian/yii2-amylian-di
=============
[![Latest Stable Version](https://poser.pugx.org/amylian/yii2-amylian-di/v/stable)](https://packagist.org/packages/amylian/yii2-amylian-di)
[![License](https://poser.pugx.org/amylian/yii2-amylian-di/license)](https://packagist.org/packages/amylian/yii2-amylian-di)
[![Build Status (master)](https://travis-ci.org/amylian/yii2-amylian-di.svg?branch=master)]https://travis-ci.org/amylian/yii2-amylian-di.svg?branch=master

| [Master][Master] | [2.7][2.7] | [2.6][2.6] | [2.5][2.5] |
|:----------------:|:----------:|:----------:|:----------:|
| [![Build status][Master image]][Master] | [![Build status][2.7 image]][2.7] | [![Build status][2.6 image]][2.6] | [![Build status][2.5 image]][2.5] |
| [![Coverage Status][Master coverage image]][Master coverage] | [![Coverage Status][2.7 coverage image]][2.7 coverage] | [![Coverage Status][2.6 coverage image]][2.6 coverage] | [![Coverage Status][2.5 coverage image]][2.5 coverage] |


**Extended Dependency Injection Container for Yii2**


Features
------------

Provides an extended implementation of an Dependency Injection Container for the Yii2 Framework

The following features are added to the standard implementation:

* *Named Parameters:* Constructor parameters do not need to be specified by order or index-position, but
  constructor parameter names can be used optionally.
* *Setter-Calls:* It's possible to specify setter-calls in the configuration array using the format
  `methodName()`. The value can be a reference (Instance or ReferenceInterface), but *must* be
  specified in an array.
* *ReferenceInterface:* Additionally to the standard Instance object, a `ReferenceInterface` is
  defined which is resolved upon object creation. This component already contains various
  implementations of `ReferenceInterface`:
  * `InstanceArray`: Can be used to define an array of references (`Instance`-objects or objects
    implementing `ReferenceInterface` which are automatically resolved by the Container when needed.
    Note: The array may contain other values. If a item is not a reference (i.E. a object of another type, 
    a string, etc.) the item is left unchanged.
  * `Alias`: Can be used to define a path containing an alias, e.g. `"@runtime/data/path"`.
    This type of reference can be used in the config array if a path is used, but the
    defined object does not support Yii-Aliases (i.E. 3rd party, not yii-specific classes).
    using `\Amylian\Yii\DI\Alias:of('@app/my/path')` does the same as `\Yii:getPath('@app/my/path')`,
    but the latter cannot be used in configuration as the aliases are unknown when the configuration
    is loaded.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist amylian/yii2-amylian-di "*"
```

or add

```
"amylian/yii2-amylian-di": "*"
```

to the require section of your `composer.json` file.


Usage
-----

### Use the extended Container Class as standard Container in Yii2:

Yii2 creates the global Container by automatically. The easiest way to replace
it with an instance of `\Amylian\Yii\DI\Container` is to add 

```php
\Yii::$container = new Amylian\Yii\DI\Container();
```

right after loading Yii.php:


```php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

\Yii::$container = new Amylian\Yii\DI\Container();  // <--- Create New Container!

(new yii\web\Application($config))->run();

```

