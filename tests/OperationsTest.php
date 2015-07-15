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
    static private $connection;

    static public function setUpBeforeClass()
    {
        $db = include(__DIR__ . "/config.php");
        $mysqltcs = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        self::$connection = new MysqltcsOperations($mysqltcs, $db['tables']['test1']);
    }

    public function testAll()
    {
        //this is the only way to guarantee the execution without errors, in fact we force to make only one test each,
        // time: NO multithread test
        $this->simpleInsertAndRead();
        $this->free();
        $this->getListNoOrder();
        $this->free();
        $this->getListOrder();
        $this->free();
        $this->general();
        $this->free();
        $this->multipleInsert();
        $this->free();
    }

    public function free()
    {
        self::$connection->deleteRows("1");
    }


    private function simpleInsertAndRead()
    {
        self::$connection->insert("value", "'test'");
        $this->assertEquals("test", self::$connection->getValue("value","id = ".self::$connection->getLastId()));
        //no data
        $this->assertNull(self::$connection->getValue("value","id = ".(self::$connection->getLastId()+10)));
    }


    private function getListNoOrder()
    {
        $expected = array();
        self::$connection->insert("value", "'test1'");
        $expected[] = array("id"=>self::$connection->getLastId(), "value"=>"test1");
        self::$connection->insert("value", "'test2'");
        $expected[] = array("value"=>"test2", "id"=>self::$connection->getLastId());
        $results = self::$connection->getList("id, value", "1");
        foreach($expected as $row)
            $this->assertTrue(in_array($row,$results));
        $this->assertEquals(count($expected), count($results));
    }

    private function getListOrder()
    {
        $expected = array();
        self::$connection->insert("value", "'test1'");
        $expected[] = array("id"=>self::$connection->getLastId(), "value"=>"test1");
        self::$connection->insert("value", "'test2'");
        $expected[] = array("id"=>self::$connection->getLastId(), "value"=>"test2");
        $this->assertEquals($expected, self::$connection->getList("id, value", "1", "id ASC"));
        $this->assertNotEquals($expected, self::$connection->getList("id, value", "1", "id DESC"));
    }

    private function general()
    {
        $this->assertEquals(array(),self::$connection->getList("*", "1"));
        self::$connection->insert("value", "'test1'");
        $this->assertNotEquals(array(),self::$connection->getList("*", "1"));
        self::$connection->deleteRows("1");
        $this->assertEquals(array(),self::$connection->getList("*", "1"));
    }

    private function multipleInsert()
    {
        self::$connection->insert("value", array("'test1'","'test2'"));
        $expected = array(array("value"=>"test1"),array("value"=>"test2"));
        $this->assertEquals($expected, self::$connection->getList("value", "1", "id ASC"));
    }
}