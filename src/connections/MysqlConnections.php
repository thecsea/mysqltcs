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

namespace it\thecsea\mysqltcs\connections;


use it\thecsea\mysqltcs\mysqltcs;


/**
 * Class MysqlConnections
 * @author      Claudio Cardinale <cardi@thecsea.it>
 * @copyright   2015 claudio cardinale
 * @version     3.0-dev
 * @package it\thecsea\mysqltcs\connections
 */
class MysqlConnections {
    /**
     * @var MysqlConnection[]
     */
    private $connections = array();

    /**
     * @var mysqltcs[]
     */
    private $clients = array();


    /**
     * @var MysqlConnections
     */
    static private $instance = null;

    /**
     * Empty private construct (for singleton)
     */
    private function __construct()
    {
    }

    /**
     *Empty private clone (for singleton)
     */
    private function __clone()
    {
    }

    /**
     * Get singleton instance
     * @return MysqlConnections
     */
    public static function getInstance()
    {
        if(self::$instance == null)
        {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    /**
     * Get a connection; new or old, we don't know this
     * @param mysqltcs $client
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $name
     * @param string $key
     * @param string $cert
     * @param string $ca
     * @return MysqlConnection
     * @throws MysqlConnectionException
     */
    public function getConnection(mysqltcs $client, $host, $user, $password, $name, $key = "", $cert = "", $ca = ""){
        //the client has already a connection
        $clientKey = array_search ($client,$this->clients);
        if($clientKey !== false)
            throw new MysqlConnectionException("The client has already a connection, it must remove it before");

        //get new key
        $clientKey = count($this->clients);

        //I get an existing connection or I create a new one
        $this->connections[$clientKey] = $this->findConnection($host, $user, $password, $name, $key, $cert, $ca);

        //return connection
        $this->clients[$clientKey] = $client;
        return $this->connections[$clientKey];
    }

    /**
     * @param mysqltcs $client
     * @throws MysqlConnectionException
     */
    public function removeClient(mysqltcs $client){
        //the client doesn't exist
        $clientKey = array_search ($client,$this->clients);
        if($clientKey === false)
            throw new MysqlConnectionException("The client doesn't exist");

        //remove client
        unset($this->connections[$clientKey]);
        unset($this->clients[$clientKey]);
        //since php has garbage collection when we have removed all clients, the mysqli connection is closed automatically
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
    private function findConnection($host, $user, $password, $name, $key = "", $cert = "", $ca = ""){
        //search a connection
        foreach($this->connections as /** @var MysqlConnection */ $connection){
            if($connection->equalsProperties($host, $user, $password, $name, $key, $cert, $ca))
                return $connection;
        }

        //I haven't find a connection, so I create a new one
        return new MysqlConnection($host, $user, $password, $name, $key, $cert, $ca);
    }
}