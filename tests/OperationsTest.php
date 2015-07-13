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
 * Class OperationsTest
 * this class test all methods of MysqltcsOperations
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 ClaudioCardinale
 * @version 3.0.0-dev
 * @package it\thecsea\mysqltcs
 */
class OperationsTest  extends \PHPUnit_Framework_TestCase{
    /**
     * @var MysqltcsOperations
     */
    private $connection;

    public function setUp()
    {
        $db = include(__DIR__ . "/config.php");
        $mysqltcs = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $this->connection = new MysqltcsOperations($mysqltcs, $db['tables']['test1']);
    }

    public function testSimpleInsertAndRead()
    {
        $this->connection->insert("value", "'test'");
        $this->assertEquals("test", $this->connection->getValue("value","id = ".$this->connection->getLastId()));
        //no data
        $this->assertNull($this->connection->getValue("value","id = ".($this->connection->getLastId()+10)));
    }
}