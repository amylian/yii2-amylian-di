amylian/yii2-amylian-di
=============
[![Latest Stable Version](https://poser.pugx.org/{package}/v/stable)](https://packagist.org/packages/{package})
[![License](https://poser.pugx.org/{package}/license)](https://packagist.org/packages/{package})
[![Total Downloads](https://poser.pugx.org/{package}/downloads)](https://packagist.org/packages/{package})
[![Monthly Downloads](https://poser.pugx.org/{package}/d/monthly)](https://packagist.org/packages/{package})
[![Daily Downloads](https://poser.pugx.org/{package}/d/daily)](https://packagist.org/packages/{package})

*Extended Dependency Injection Container for Yii2*


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
php composer.phar require --prefer-dist {package} "*"
```

or add

```
"{package}": "*"
```

to the require section of your `composer.json` file.


Usage
-----
