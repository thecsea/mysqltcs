<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 18/07/15
 * Time: 16.00
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

require_once(__DIR__."/../vendor/autoload.php");
$db = require(__DIR__."/config.php");

print "create two connections with same aprameters (you have to set to false the parameter number 5)<br>\n";
$connection = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db'],false);
$connection2 = new Mysqltcs($db['host'], $db['user'], $db['psw'], $db['db'],false);

print "they represent the same connection(thread id)<br/>\n";
print $connection->getConnectionThreadId()."<br/>\n";
print $connection2->getConnectionThreadId()."<br/>\n";