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
        $db = require(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertTrue( $connection->isConnected());
    }

    public function testTwoNewConnection()
    {
        $db = require(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection2 = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertNotEquals($connection->getConnectionThreadId(), $connection2->getConnectionThreadId());
    }

    public function testTwoNoNewConnection()
    {
        $db = require(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db'],false);
        $connection2 = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db'], false);
        $this->assertEquals($connection->getConnectionThreadId(), $connection2->getConnectionThreadId());
    }

    public function testCloneConnection()
    {
        $db = require(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $connection2 = clone $connection;
        $connection3 = clone $connection;
        $this->assertNotEquals($connection, $connection2);
        $this->assertNotEquals($connection, $connection3);
        $this->assertNotEquals($connection2, $connection3);
        $this->assertNotEquals($connection->getInstanceNumber(), $connection2->getInstanceNumber());
        $this->assertNotEquals($connection->getInstanceNumber(), $connection3->getInstanceNumber());
        $this->assertNotEquals($connection2->getInstanceNumber(), $connection3->getInstanceNumber());
        $this->assertTrue( $connection->isConnected());
        $this->assertTrue( $connection2->isConnected());
        $this->assertTrue( $connection3->isConnected());
    }

    public function testNotEquals(){
        $db = require(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $connection2 = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $this->assertNotEquals($connection, $connection2);
        $this->assertNotEquals($connection->getInstanceNumber(), $connection2->getInstanceNumber());
        $this->assertTrue($connection !== $connection2);
        $this->assertTrue($connection != $connection2);
        $this->assertTrue( $connection->isConnected());
        $this->assertTrue( $connection2->isConnected());
    }

    public function testCloneNewConnection()
    {
        $db = require(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection2 = clone $connection;
        $this->assertNotEquals($connection, $connection2);
        $this->assertNotEquals($connection->getConnectionThreadId(), $connection2->getConnectionThreadId());
    }

    public function testCloneNoNewConnection()
    {
        $db = require(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db'],false);
        $connection2 = clone $connection;
        $this->assertNotEquals($connection, $connection2);
        $this->assertEquals($connection->getConnectionThreadId(), $connection2->getConnectionThreadId());
    }

    public function testToString()
    {
        $db = require(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $expected = ("instance number: ".$connection->getInstanceNumber()."\nhost: ".$db['host'] ."\nuser: ".$db['user'] ."\npassword: ".$db['psw']  ."\nname: ".$db['db'] ."\nkey: "."" ."\ncert: "."" ."\nca: "."\nnew conenction: "."true"."\nconnection thread id: ".$connection->getConnectionThreadId());
        $this->assertEquals((String)$connection , $expected);
    }

    /**
     * @expectedException \it\thecsea\mysqltcs\connections\utils\MysqlUtilsException
     */
    public function testNoConnection()
    {
        $db = require(__DIR__."/config.php");
        new Mysqltcs($db['host'],  $db['user']."wrong", $db['psw'], $db['db']);
    }

    /**
     * @expectedException \it\thecsea\mysqltcs\MysqltcsException
     */
    public function testNoConnectionMainException()
    {
        $db = require(__DIR__."/config.php");
        new Mysqltcs($db['host'],  $db['user']."wrong", $db['psw'], $db['db']);
    }
}
