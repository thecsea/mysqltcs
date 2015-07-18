<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 18/07/15
 * Time: 16.11
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
use it\thecsea\mysqltcs\Mysqltcs;
use it\thecsea\mysqltcs\SimpleLogger;
use it\thecsea\mysqltcs\MysqltcsException;

require_once(__DIR__."/../vendor/autoload.php");
$db = require(__DIR__."/config.php");

$connection = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db'],false);

print "create logger<br>\n";
//you can create your logger that implements MysqltcsLogger
$logger = new SimpleLogger();
$connection->setLogger($logger); //you can als do in the following way: $connection->setSimpleLogger() and get logger with $connection->getLogger();

print "Simple correct query<br/>\n";
$connection->executeQuery("show tables");
print "Query with error<br/>\n";
try{
    $connection->executeQuery("no sql");
}catch(MysqltcsException $e){}

print "Log data<br/>\n";
$data = $logger->getLogArray();
foreach($data as $ele)
    print $ele."<br/>\n";

print "if you use simpleLogger, you can also print the log when the query is performed<br/>\n";
$logger->setPrint(true);
$connection->executeQuery("show tables");
