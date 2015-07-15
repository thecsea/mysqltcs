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
    /**
     * @var Mysqltcs
     */
    private $connection;

    public function setUp()
    {
        $db = include(__DIR__ . "/config.php");
        $this->connection = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
    }
    public function testSimpleExecute()
    {
        $this->connection->executeQuery("show tables");
    }

    /**
     * @expectedException \it\thecsea\mysqltcs\MysqltcsException
     */
    public function testMysqlError()
    {
        $this->connection->executeQuery("no sql");
    }

    public function testSimpleLogger()
    {
        $logger = new SimpleLogger();
        $this->connection->setLogger($logger);
        $this->connection->executeQuery("show tables");
        try {
            $this->connection->executeQuery("no sql");
        }catch(MysqltcsException $e){}
        $logA = $logger->getLogArray();//this way to keep the php 5.3 compatibility
        $this->assertEquals($logA[0], "show tables");
        $this->assertEquals(substr($logA[1],0,strlen("Mysql error")), "Mysql error");
    }

    public function testSetSimpleLogger()
    {
        $this->connection->setSimpleLogger();
        $logger = /** @var SimpleLogger */ $this->connection->getLogger();
        $this->connection->executeQuery("show tables");
        try {
            $this->connection->executeQuery("no sql");
        }catch(MysqltcsException $e){}
        $logA = $logger->getLogArray();//this way to keep the php 5.3 compatibility
        $this->assertEquals($logA[0], "show tables");
        $this->assertEquals(substr($logA[1],0,strlen("Mysql error")), "Mysql error");
    }

    public function testLoggerPrint()
    {
        $logger = new SimpleLogger();
        $this->connection->setLogger($logger);
        $this->assertFalse($logger->isPrint());
        $logger->setPrint(true);
        $this->assertTrue($logger->isPrint());
        $this->expectOutputString("show tables\n");
        $this->connection->executeQuery("show tables");
        $logger->setPrint(false);
        $this->assertFalse($logger->isPrint());
    }
}