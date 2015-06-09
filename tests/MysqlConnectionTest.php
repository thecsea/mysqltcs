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
        $expected = ("host: ".$db['host'] ."\nuser: ".$db['user'] ."\npassword: ".$db['psw']  ."\nname: ".$db['db'] ."\nkey: "."" ."\ncert: "."" ."\nca: "."");
        $this->assertEquals((String)$connection , $expected);
    }

    public function testEqualsProperty()
    {
        $db = include(__DIR__."/config.php");
        $connection = new MysqlConnection($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertTrue($connection->equalsProperties($db['host'],  $db['user'], $db['psw'], $db['db']));
        $this->assertFalse($connection->equalsProperties($db['host']."NO",  $db['user'], $db['psw'], $db['db']));
    }
}