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
    public function testOneRemove()
    {
        $db = require(__DIR__."/config.php");
        $mysqlConnections = MysqlConnections::getInstance();
        $obj = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $mysqlConnections->getConnection($obj, $db['host'], $db['user'], $db['psw'], $db['db']);
        $mysqlConnections->removeClient($obj);
        $this->setExpectedException('\it\thecsea\mysqltcs\connections\MysqlConnectionException');
        $mysqlConnections->removeClient($obj);
    }

    public function testOneRemoveGeneralException()
    {
        $db = require(__DIR__."/config.php");
        $mysqlConnections = MysqlConnections::getInstance();
        $obj = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $mysqlConnections->getConnection($obj, $db['host'], $db['user'], $db['psw'], $db['db']);
        $mysqlConnections->removeClient($obj);
        $this->setExpectedException('\it\thecsea\mysqltcs\MysqltcsException');
        $mysqlConnections->removeClient($obj);
    }
}
