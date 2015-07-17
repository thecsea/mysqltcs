# mysqltcs
Build status: [![Build Status](https://travis-ci.org/thecsea/mysqltcs.svg?branch=master)](https://travis-ci.org/thecsea/mysqltcs) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecsea/mysqltcs/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/thecsea/mysqltcs/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/thecsea/mysqltcs/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/thecsea/mysqltcs/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/thecsea/mysqltcs/badges/build.png?b=master)](https://scrutinizer-ci.com/g/thecsea/mysqltcs/build-status/master)

A simple library for mysql written in php

In this class you can use the same db connection in more than one instances

This library allow you to make common database operations immediately 

# Download and install

## Download

### via git
Clone repository

`git clone https://github.com/thecsea/mysqltcs.git`

### via composer
add the following dependence 

`"thecsea/mysqltcs": "dev-master"`

##Install

Execute composer (download composer here https://getcomposer.org/)

`php composer.phar install`


# Tests
Change db data in `tests/config.php`

Import `tests/mysqltcs.sql`

Execute the unit tests:

1. Go in the root directory
2. Type `phpunit` or if you have downloaded the phar `php phpunit-xxx.phar`

In fact `phpunit.xml` contains the correcttest configuration

**CAUTION**: each time tests are executed, the database must be in the initial state, like the import has just been executed (you should have a empty  table, only the db structure)

# By [thecsea.it](http://www.thecsea.it)
