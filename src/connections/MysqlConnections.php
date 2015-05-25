<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 22/05/15
 * Time: 22.10
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

namespace it\thecsea\mysqlTCS\connections;


use it\thecsea\mysqlTCS\MysqlTCS;
use it\thecsea\mysqlTCS\MysqlConnectionException;
use it\thecsea\mysqlTCS\MysqlConnection;

/**
 * Class MysqlConnections
 * @package it\thecsea\mysqlTCS\connections
 */
class MysqlConnections {
    /**
     * @var array
     */
    static private $connections = array();

    /**
     * Get a connection; new or old, we don't know this
     * @param MysqlTCS $client
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $name
     * @param string $key
     * @param string $cert
     * @param string $ca
     * @return \mysqli
     * @throws MysqlConnectionException
     */
    static public function getConnection(MysqlTCS $client, $host, $user, $password, $name, $key = "", $cert = "", $ca = ""){
        //the client has already a connection
        if(isset(self::$connections[$client]))
            throw new MysqlConnectionException("The client has already a connection, it must remove it before");

        //I get an existing connection or I create a new one
        self::$connections[$client] = self::findConnection($host, $user, $password, $name, $key, $cert, $ca);

        //return connection
        return self::$connections[$client]->getMysqli();
    }

    /**
     * @param MysqlTCS $client
     * @throws MysqlConnectionException
     */
    static public function removeClient(MysqlTCS $client){
        //the client doesn't exist
        if(!isset(self::$connections[$client]))
            throw new MysqlConnectionException("The client doesn't exist");

        //remove client
        unset(self::$connections[$client]);
        //since php ha garbage collection when we have removed all clients, the mysqli connection is closed automatically
    }

    /**
     * Get a connection if it exists, otherwise it creates a new one
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $name
     * @param string $key
     * @param string $cert
     * @param string $ca
     * @return MysqlConnection
     */
    static private function findConnection($host, $user, $password, $name, $key = "", $cert = "", $ca = ""){
        //search a connection
        foreach(self::$connections as /** @var MysqlConnection */ $connection){
            if($connection->equalsProperties($host, $user, $password, $name, $key, $cert, $ca))
                return $connection;
        }

        //I haven't find a connection, so I create a new one
        return new MysqlConnection($host, $user, $password, $name, $key, $cert, $ca);
    }
}