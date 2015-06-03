<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03/06/15
 * Time: 2.50
 */

namespace it\thecsea\mysqlTCS;
 require_once(__DIR__."/../vendor/autoload.php");


class TestConnnections extends \PHPUnit_Framework_TestCase {

    public function testOneConnection(){
        $db = include(__DIR__."/config.php");
        $connection = new MysqlTCS($db['host'],  $db['user'], $db['psw'], $db['db']);
        $this->assertTrue( $connection->isConnected());
    }
}
