<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 09/06/15
 * Time: 10.15
 */

namespace it\thecsea\mysqltcs;

require_once(__DIR__ . "/../vendor/autoload.php");

/**
 * Class SimpleOperationsTest
 * this class make basic tests on MysqltcsOperations
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 ClaudioCardinale
 * @version 3.0.0-dev
 * @package it\thecsea\mysqltcs
 */
class SimpleOperationsTest  extends \PHPUnit_Framework_TestCase{
    public function testSimpleTests()
    {
        $db = include(__DIR__ . "/config.php");
        $mysqltcs = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $connection = new MysqltcsOperations($mysqltcs);
        $connection->showDatabases();
        $tables = $connection->showTables();
        $this->assertTrue(in_array($db['tables']['test1'],$tables));
    }

    public function testGetterSetter()
    {
        $db = include(__DIR__ . "/config.php");
        $mysqltcs = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $connection = new MysqltcsOperations($mysqltcs, "t1");
        $connection->setFrom("frm1");
        $this->assertEquals ($connection->getFrom(), "frm1");
        $connection->setQuotes(false);
        $this->assertFalse ($connection->isQuotes());
        $mysqltcs2 = clone $mysqltcs;
        $connection->setMysqltcs($mysqltcs2);
        $this->assertNotEquals ($connection->getMysqltcs(), $mysqltcs);
        $this->assertEquals ($connection->getMysqltcs(), $mysqltcs2);
    }

    public function testClone()
    {
        $db = include(__DIR__ . "/config.php");
        $mysqltcs = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $connection = new MysqltcsOperations($mysqltcs);
        $connection2 = clone $connection;
        $connection->showDatabases();
        $connection2->showDatabases();
    }
}