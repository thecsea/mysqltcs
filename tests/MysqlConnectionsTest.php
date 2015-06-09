<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 09/06/15
 * Time: 10.31
 */

namespace it\thecsea\mysqltcs;
use it\thecsea\mysqltcs\connections\MysqlConnections;


/**
 * Class MysqlConnectionsTest
 * this class test methods of MysqlConnections that are not tested in other tests
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 ClaudioCardinale
 * @version 3.0.0-dev
 * @package it\thecsea\mysqltcs
 */
class MysqlConnectionsTest extends \PHPUnit_Framework_TestCase {
    /**
     * @expectedException \it\thecsea\mysqltcs\connections\MysqlConnectionException
     */
    public function testOneRemove()
    {
        $db = include(__DIR__."/config.php");
        $mysqlConnections = MysqlConnections::getInstance();
        $obj = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $mysqlConnections->getConnection($obj, $db['host'], $db['user'], $db['psw'], $db['db']);
        $mysqlConnections->removeClient($obj);
        $this->assertTrue(true); //test no exception in previous instruction
        $mysqlConnections->removeClient($obj);
    }

    /*
     *  $this->assertTrue(true);

        $db = include(__DIR__."/config.php");
        $mysqlConnections = MysqlConnections::getInstance();
        $obj1 = new \stdClass();
        $conn1 = $this->mysqlConnections->getConnection($obj1, $this->host, $this->user, $this->password, $this->name, $this->key, $this->cert, $this->ca);
        $obj2 = new \stdClass();
        $conn2 = $this->mysqlConnections->getConnection($obj2, $this->host, $this->user, $this->password, $this->name, $this->key, $this->cert, $this->ca);
        $connection = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $expected = ("host: ".$db['host'] ."\nuser: ".$db['user'] ."\npassword: ".$db['psw']  ."\nname: ".$db['db'] ."\nkey: "."" ."\ncert: "."" ."\nca: "."");
        $this->assertEquals((String)$connection , $expected);
     */
}
