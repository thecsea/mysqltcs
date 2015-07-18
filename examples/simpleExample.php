<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 18/07/15
 * Time: 1.23
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
use it\thecsea\mysqltcs\MysqltcsOperations;
use it\thecsea\mysqltcs\MysqltcsException;

require_once(__DIR__."/../vendor/autoload.php");
$db = require(__DIR__."/config.php");

print "<h1>Connection</h1>\n";
//catch wrong connection
print "<h2>Test connection</h2>\n";
print "<h3>Wrong data</h3>\n";
try {
    $connection = new Mysqltcs($db['host'], $db['user']."wrong", $db['psw'], $db['db']);
}catch(MysqltcsException $e)
{
    print "Error on connection caught<br/>\n";
    print $e->getMessage()."<br/>\n";
}

print "<h3>Correct data</h3>\n";
//correct connection
try {
    $connection = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db']);
    print "No error<br/>\n";
}catch(MysqltcsException $e)
{
}


print "<h2>Simple query</h2>\n";
print "<h3>Correct query(show tables)</h3>\n";

//simple query
$results = $connection->executeQuery("show tables");
while ($row = $results->fetch_array()) {
    print $row[0]."<br/>\n";
}


print "<h3>Error on query</h3>\n";
//if an error is occurred mysqltcs throw an exception
try {
    $results = $connection->executeQuery("No sql");
}catch(MysqltcsException $e)
{
    print "Error on query caught<br/>\n";
    print $e->getMessage()."<br/>\n";
}

print "<h1>Simple operations</h1>\n";
//now we use MysqltcsOperations that allows to make common operations immediately in a specified table
$operations = new MysqltcsOperations($connection, $db['tables']['test1']);

print "<h2>Insert</h2>\n";
print "<h2>Wrong insert</h2>\n";
try {
    $operations->insert("value1gg","'tt'");
}catch(MysqltcsException $e)
{
    print "Error on insert caught<br/>\n";
    print $e->getMessage()."<br/>\n";
}

print "<h2>Multiple insert(value - value2)</h2>\n";
$operations->insert("value,value2",array("'1','aa'", "'2','ba'"));

print "<h2>Simple insert(value - value2)</h2>\n";
$operations->insert("value,value2","'1','bb'");


print "<h2>Id of last insert</h2>\n";
print $id = $operations->getLastId();

print "<h2>Get</h2>\n";
print "obviously you can catch exception if you make a wrong get";
print "<h3>Get 'value2' of $id</h3>\n";
print $operations->getValue("value2", "id = $id");

print "<h3>Get all data</h3>\n";
$results = $operations->getList("id, value, value2", "1"); // or $results = $operations->getList("*", "1");
print "id-value-value2<br/>\n";
foreach($results as $value)
    print $value['id']."-".$value['value']."-".$value['value2']."<br/>\n";

print "<h2>Escape</h2>\n";
$escape = "wro'ng";
print "<h3>insert without escape of `$escape`</h3>\n";
try {
    $operations->insert("value", "'$escape'");
}catch(MysqltcsException $e)
{
    print "Error on insert caught<br/>\n";
    print $e->getMessage()."<br/>\n";
}

print "<h3>insert with escape of `$escape`</h3>\n";
$operations->insert("value", "'".$operations->getEscapedString($escape)."'");

$results = $operations->getList("id, value, value2", "1"); // or $results = $operations->getList("*", "1");
print "id-value-value2<br/>\n";
foreach($results as $value)
    print $value['id']."-".$value['value']."-".$value['value2']."<br/>\n";

print "<h2>Remove</h2>\n";
print "<h3>Remove $id</h3>\n";
$operations->deleteRows("id = $id");
$results = $operations->getList("id, value, value2", "1"); // or $results = $operations->getList("*", "1");
print "id-value-value2<br/>\n";
foreach($results as $value)
    print $value['id']."-".$value['value']."-".$value['value2']."<br/>\n";
print "<h3>Remove all rows</h3>\n";
$operations->deleteRows("1");
$results = $operations->getList("id, value, value2", "1"); // or $results = $operations->getList("*", "1");
print "id-value-value2<br/>\n";
foreach($results as $value)
    print $value['id']."-".$value['value']."-".$value['value2']."<br/>\n";