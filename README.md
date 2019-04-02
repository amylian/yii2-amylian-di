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
  defined which is resolved upon object creation. The component contains an implmentation of `InstanceArray`
  which may contain references to other objects which are resolved upon object initiation via
  DI.


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
