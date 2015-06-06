<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03/06/15
 * Time: 23.24
 */

namespace it\thecsea\mysqltcs;
require_once(__DIR__ . "/../vendor/autoload.php");

/**
 * Class TestExecute
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 ClaudioCardinale
 * @version 3.0.0-dev
 * @package it\thecsea\mysqltcs
 */
class ExecuteTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleExecute()
    {
        $db = include(__DIR__ . "/config.php");
        $connection = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $connection->executeQuery("show tables");
    }

    /**
     * @expectedException \it\thecsea\mysqltcs\MysqltcsException
     */
    public function testMysqlError()
    {
        $db = include(__DIR__ . "/config.php");
        $connection = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $connection->executeQuery("no sql");
    }

    public function testLogger()
    {
        $db = include(__DIR__ . "/config.php");
        $connection = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $logger = new SimpleLogger();
        $connection->setLogger($logger);
        $connection->executeQuery("show tables");
        try {
            $connection->executeQuery("no sql");
        }catch(MysqltcsException $e){}
        $logA = $logger->getLogArray();//this way to keep the php 5.3 compatibility
        $this->assertEquals($logA[0], "show tables");
        $this->assertEquals(substr($logA[1],0,strlen("Mysql error")), "Mysql error");
    }
}