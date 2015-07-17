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
        $db = require(__DIR__ . "/config.php");
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
        $this->removeNumber();
        $this->free();
        $this->update();
        $this->free();
        $this->escape();
        $this->free();
        $this->simpleChain();
        $this->free();
    }

    public function free()
    {
        return self::$connection->deleteRows("1");
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
        {
            $this->assertTrue(in_array($row, $results));
        }
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

    private function removeNumber()
    {
        $this->assertEquals(0, $this->free());
        self::$connection->insert("value", array("'test1'","'test2'"));
        $this->assertEquals(2, $this->free());
    }

    private function update()
    {
        self::$connection->insert("value, value2", "'test1', 'test3'");
        $id = self::$connection->getLastId();
        self::$connection->update(array("value" => "'test2'"),"id = ".$id);
        $this->assertEquals("test2", self::$connection->getValue("value", "id = ".$id));
        $this->assertEquals("test3", self::$connection->getValue("value2", "id = ".$id));
    }

    private function escape()
    {
        $text = "tes't1";
        self::$connection->insert("value", "'" . self::$connection->getEscapedString($text) . "'");
        $thrown = false;
        try {
            self::$connection->insert("value", "'" . $text . "'");
        } catch (MysqltcsException $e) {
            $thrown = true;
        }
        $this->assertTrue($thrown);
    }

    private function simpleChain()
    {
        $db = require(__DIR__ . "/config.php");
        $expected = array();
        self::$connection->insert($db['tables']['test1'].".value", "'test1'");
        $expected[] = array("id"=>self::$connection->getLastId(), "value"=>"test1");
        self::$connection->insert($db['tables']['test1'].".value", "'test2'");
        $id = self::$connection->getLastId();
        self::$connection->update(array($db['tables']['test1'].".value" => "'test3'"),$db['tables']['test1'].".id = ".$id);
        $expected[] = array("id"=>$id, "value"=>"test3");
        $this->assertEquals($expected, self::$connection->getList($db['tables']['test1'].".id, ".$db['tables']['test1'].".value", "1", "id ASC"));
        $this->assertNotEquals($expected, self::$connection->getList($db['tables']['test1'].".id, ".$db['tables']['test1'].".value", "1", "id DESC"));
        foreach($expected as $row)
        {
            self::$connection->deleteRows($db['tables']['test1'].".id = ".$row['id']);
        }
        $this->assertEquals(array(),self::$connection->getList("*", "1"));
    }
}