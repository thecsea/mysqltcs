<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 09/06/15
 * Time: 10.15
 */

namespace it\thecsea\mysqltcs;
use it\thecsea\mysqltcs\connections\MysqlConnection;

require_once(__DIR__ . "/../vendor/autoload.php");

/**
 * Class MysqlConnectionTest
 * this class test methods of MysqlConnection that are not tested in other tests
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 ClaudioCardinale
 * @version 3.0.0-dev
 * @package it\thecsea\mysqltcs
 */
class MysqlConnectionTest  extends \PHPUnit_Framework_TestCase{
    public function testToString()
    {
        $db = include(__DIR__."/config.php");
        $connection = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $expected = ("instance number: ".$connection->getInstanceNumber()."\nhost: ".$db['host'] ."\nuser: ".$db['user'] ."\npassword: ".$db['psw']  ."\nname: ".$db['db'] ."\nkey: "."" ."\ncert: "."" ."\nca: "."");
        $this->assertEquals((String)$connection, $expected);
    }

    public function testEqualsProperty()
    {
        $db = include(__DIR__."/config.php");
        $connection = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertTrue($connection->equalsProperties($db['host'],  $db['user'], $db['psw'], $db['db']));
        $this->assertFalse($connection->equalsProperties($db['host']."NO",  $db['user'], $db['psw'], $db['db']));
    }

    public function testNotEqual()
    {
        $db = include(__DIR__."/config.php");
        $connection = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection2 = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertNotEquals($connection, $connection2);
        $this->assertTrue($connection !== $connection2);
        $this->assertTrue($connection != $connection2);
    }

    public function testCloneConnection()
    {
        $db = include(__DIR__."/config.php");
        $connection = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection2 = clone $connection;
        $connection3 = clone $connection;
        $this->assertNotEquals($connection, $connection2);
        $this->assertNotEquals($connection, $connection3);
        $this->assertNotEquals($connection2, $connection3);
        $this->assertNotEquals($connection->getInstanceNumber(), $connection2->getInstanceNumber());
        $this->assertNotEquals($connection->getInstanceNumber(), $connection3->getInstanceNumber());
        $this->assertNotEquals($connection2->getInstanceNumber(), $connection3->getInstanceNumber());
    }

    public function testNoConnectClone()
    {
        $db = include(__DIR__."/config.php");
        $connection = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection2 = clone $connection;
        $this->assertEquals($connection->getMysqli(), null);
        $this->assertEquals($connection2->getMysqli(), null);
        $connection->connect();
        $connection2->connect();
        $this->assertNotEquals($connection->getMysqli()->thread_id, $connection2->getMysqli()->thread_id);
    }

    public function testConnectClone()
    {
        $db = include(__DIR__."/config.php");
        $connection = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection->connect();
        $connection2 = clone $connection;
        $this->assertNotEquals($connection->getMysqli()->thread_id, $connection2->getMysqli()->thread_id);
    }

    public function testNoConnect()
    {
        $db = include(__DIR__."/config.php");
        $connection = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection2 = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertEquals($connection->getMysqli(), null);
        $this->assertEquals($connection2->getMysqli(), null);
        $connection->connect();
        $connection2->connect();
        $this->assertNotEquals($connection->getMysqli()->thread_id, $connection2->getMysqli()->thread_id);
    }

    public function testConnect()
    {
        $db = include(__DIR__."/config.php");
        $connection = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection->connect();
        $connection2 = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertNotEquals($connection->getMysqli(), null);
        $this->assertEquals($connection2->getMysqli(), null);
        $connection2->connect();
        $this->assertNotEquals($connection->getMysqli()->thread_id, $connection2->getMysqli()->thread_id);
    }
}