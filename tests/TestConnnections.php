<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03/06/15
 * Time: 2.50
 */

namespace it\thecsea\mysqltcs;
 require_once(__DIR__."/../vendor/autoload.php");


class TestConnnections extends \PHPUnit_Framework_TestCase {

    public function testOneConnection(){
        $db = include(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertTrue( $connection->isConnected());
    }

    public function testTwoNewConnection(){
        $db = include(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $connection2 = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertNotEquals($connection->getConnectionThreadId(), $connection2->getConnectionThreadId());
    }

    public function testTwoNoNewConnection(){
        $db = include(__DIR__."/config.php");
        $connection = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db'],false);
        $connection2 = new Mysqltcs($db['host'],  $db['user'], $db['psw'], $db['db'], false);
        $this->assertEquals($connection->getConnectionThreadId(), $connection2->getConnectionThreadId());
    }
}
