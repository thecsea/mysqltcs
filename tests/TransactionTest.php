<?php
/**
 * Created by PhpStorm.
 * User: Claudio Cardinale <cardi@thecsea.it>
 * Date: 05/10/15
 * Time: 15.38
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace it\thecsea\mysqltcs;


/**
 * Class TransactionTest
 * @package it\thecsea\mysqltcs
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 Claudio Cardinale
 * @version 1.0.0
 */
class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfull()
    {
        $db = require(__DIR__ . "/config.php");
        $mysqltcs = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $mysqltcs2 = clone $mysqltcs;
        $operations = new MysqltcsOperations($mysqltcs, $db['tables']['test1']);
        $operations2 = clone $operations;
        $operations2->setMysqltcs($mysqltcs2);
        //commit
        $mysqltcs->setAutocommit(false);
        $operations->insert("value, value2", "'tt', 'kk'");
        $this->assertEmpty($operations2->getList("*", "1"));
        $mysqltcs2->commit();
        $this->assertEmpty($operations2->getList("*", "1"));
        $mysqltcs->commit();
        $this->assertNotEmpty($operations2->getList("*", "1"));
        $mysqltcs->setAutocommit(true);
        $operations->deleteRows("1");
        //autocommit
        $mysqltcs->setAutocommit(false);
        $operations->insert("value, value2", "'tt', 'kk'");
        $this->assertEmpty($operations2->getList("*", "1"));
        $mysqltcs->setAutocommit(true);
        $this->assertNotEmpty($operations2->getList("*", "1"));
        $operations->deleteRows("1");
        //rollback
        $mysqltcs->setAutocommit(false);
        $operations->insert("value, value2", "'tt', 'kk'");
        $this->assertEmpty($operations2->getList("*", "1"));
        $mysqltcs->rollBack();
        $this->assertEmpty($operations2->getList("*", "1"));
        $mysqltcs->setAutocommit(true);
        $this->assertEmpty($operations2->getList("*", "1"));
        $operations->deleteRows("1"); //this to avoid problems in other tests, in normal situations thi lines is useless
    }

    public function testLogger()
    {
        $db = require(__DIR__ . "/config.php");
        $mysqltcs = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
        $mysqltcs->setSimpleLogger();
        $mysqltcs->setAutocommit(false);
        $logA = $mysqltcs->getLogger()->getLogArray();
        $this->assertEquals("autocommit set to false", $logA[count($logA)-1]);
        $mysqltcs->commit();
        $logA = $mysqltcs->getLogger()->getLogArray();
        $this->assertEquals("commit", $logA[count($logA)-1]);
        $mysqltcs->rollBack();
        $logA = $mysqltcs->getLogger()->getLogArray();
        $this->assertEquals("rollback", $logA[count($logA)-1]);
    }
}
