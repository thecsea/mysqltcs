# mysqltcs
Build status: [![Build Status](https://travis-ci.org/thecsea/mysqltcs.svg?branch=master)](https://travis-ci.org/thecsea/mysqltcs) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecsea/mysqltcs/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thecsea/mysqltcs/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/thecsea/mysqltcs/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/thecsea/mysqltcs/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/thecsea/mysqltcs/badges/build.png?b=master)](https://scrutinizer-ci.com/g/thecsea/mysqltcs/build-status/master) [![Latest Stable Version](https://poser.pugx.org/thecsea/mysqltcs/v/stable)](https://packagist.org/packages/thecsea/mysqltcs) [![Total Downloads](https://poser.pugx.org/thecsea/mysqltcs/downloads)](https://packagist.org/packages/thecsea/mysqltcs) [![Latest Unstable Version](https://poser.pugx.org/thecsea/mysqltcs/v/unstable)](https://packagist.org/packages/thecsea/mysqltcs) [![License](https://poser.pugx.org/thecsea/mysqltcs/license)](https://packagist.org/packages/thecsea/mysqltcs)


A simple and powerful library for mysql written in php:

* You can use the same db connection in more than one instances (optimizing mysql physical connections)
* This library allow you to make common database operations immediately and efficiently, returning simple data structures
* Log all actions performed on db
* All mysql error as exception
* Clone support

# Download, install and use

## Download

### via git
Clone repository

`git clone https://github.com/thecsea/mysqltcs.git`

### via composer
add the following dependence 

`"thecsea/mysqltcs": "dev-master"`

or type

`composer require thecsea/mysqltcs`

##Install/Update
Execute composer (download composer here https://getcomposer.org/)
###Install

`php composer.phar install`

###Update

`php composer.phar update`

you have to perform an update when a new version is released

##How to use

When composer installation is finished you will see `vendor/autoload.php` that is the auload generated by composer. If you have set `mysqltcs` as composer dependence the autoload loads both mysqltcs and other dependecies. So you just have to include autload in each file where you want use mysqltcs and create the mysqtcs object in the following way:

`$connection = new it\thecsea\mysqltcs\Mysqltcs(...);`

or

`use it\thecsea\mysqltcs\Mysqltcs;` and `$connection = new Mystcs(...);`

You can see [examples](#examples) to understand how to use library

You can also take a look to [wiki](https://github.com/thecsea/mysqltcs/wiki) to see the detailed description or go to generated [phpdoc documents](http://thecsea.github.io/mysqltcs/namespaces/it.thecsea.mysqltcs.html) (documentations of all classes)

# Tests
Mysqltcs is tested with automatic test: phpunit test. So we have a good chance of not having errors, but not the 
certainty.
But we have covered the following percentage of statements with tests: [![Code Coverage](https://scrutinizer-ci.com/g/thecsea/mysqltcs/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/thecsea/mysqltcs/?branch=master)

* Change db data in `tests/config.php`
* Import `tests/mysqltcs.sql`
* Execute the unit tests:

1. Go in the root directory
2. Type `phpunit` or if you have downloaded the phar `php phpunit-xxx.phar`

In fact `phpunit.xml` contains the correct test configuration

**CAUTION**: each time tests are executed, the database must be in the initial state, like the import has just been executed (you should have a empty  table, only the db structure)

#Examples
You can find some examples under `examples` to run it:

* Change db data in `examples/config.php`
* Import `examples/mysqltcs.sql`

There is a simple example  `simpleExample.php` that shows you how to use the library in the simplest way

# By [thecsea.it](http://www.thecsea.it)
