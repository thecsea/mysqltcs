<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03/06/15
 * Time: 2.50
 */

namespace it\thecsea\mysqltcs;
require_once(__DIR__."/../vendor/autoload.php");


/**
 * Class TestConnnections
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 ClaudioCardinale
 * @version 3.0.0-dev
 * @package it\thecsea\mysqltcs
 */
class ConnnectionsTest extends \PHPUnit_Framework_TestCase {

    public function testOneConnection()
    {
        $db = include(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertTrue( $connection->isConnected());
    }

    public function testTwoNewConnection()
    {
        $db = include(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection2 = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertNotEquals($connection->getConnectionThreadId(), $connection2->getConnectionThreadId());
    }

    public function testTwoNoNewConnection()
    {
        $db = include(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db'],false);
        $connection2 = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db'], false);
        $this->assertEquals($connection->getConnectionThreadId(), $connection2->getConnectionThreadId());
    }

    public function testCloneConnection()
    {
        $db = include(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $connection2 = clone $connection;
        $this->assertNotEquals($connection, $connection2);
        $this->assertTrue( $connection->isConnected());
        $this->assertTrue( $connection2->isConnected());
    }

    public function testCloneNewConnection()
    {
        $db = include(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection2 = clone $connection;
        $this->assertNotEquals($connection, $connection2);
        $this->assertNotEquals($connection->getConnectionThreadId(), $connection2->getConnectionThreadId());
    }

    public function testCloneNoNewConnection()
    {
        $db = include(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db'],false);
        $connection2 = clone $connection;
        $this->assertNotEquals($connection, $connection2);
        $this->assertEquals($connection->getConnectionThreadId(), $connection2->getConnectionThreadId());
    }
}
