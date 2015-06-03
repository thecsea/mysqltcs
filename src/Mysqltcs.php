<?php
/**
 * Created by PhpStorm.
 * User: Claudio Cardinale
 * Date: 22/05/15
 * Time: 21.48
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
use it\thecsea\mysqltcs\connections\MysqlConnection;
use it\thecsea\mysqltcs\connections\MysqlConnectionException;
use it\thecsea\mysqltcs\connections\MysqlConnections;


/**
 * Class mysqltcs
 * @author      Claudio Cardinale <cardi@thecsea.it>
 * @copyright   2015 claudio cardinale
 * @version     3.0-dev
 * @package it\thecsea\mysqltcs
 */
class Mysqltcs {

    /**
     * @var String
     */
    private $host;
    /**
     * @var String
     */
    private $user;
    /**
     * @var String
     */
    private $password;
    /**
     * @var String
     */
    private $name;
    /**
     * @var bool
     */
    private $newConnection;
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $cert;
    /**
     * @var string
     */
    private $ca;
    /**
     * @var MysqlConnection
     */
    private $mysqlRef;

    /**
     * @var MysqlConnections
     */
    private $mysqlConnections;

    /**
     * Get a connection to mysql
     * @param String $host
     * @param String $user
     * @param String $password
     * @param String $name
     * @param bool $newConnection optional, default true. If it is false the class uses an already open connection if it possible
     * @param string $key optional
     * @param string $cert optional
     * @param string $ca optional
     */
    public function __construct($host, $user, $password, $name, $newConnection = true, $key = "", $cert = "", $ca = "")
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->name = $name;
        $this->newConnection = $newConnection;
        $this->key = $key;
        $this->cert = $cert;
        $this->ca = $ca;
        $this->mysqlConnections = MysqlConnections::getInstance();
        $this->getConnection();
    }

    /**
     * @throws MysqlConnectionException
     */
    public function __destruct()
    {
        if(!$this->newConnection)
            $this->mysqlConnections->removeClient($this);

    }

    /**
     * Get the connection according to newConnection value
     * @throws MysqlConnectionException
     */
    private function getConnection()
    {
        if($this->newConnection)
        {
            $this->mysqlRef = new MysqlConnection($this->host, $this->user, $this->password, $this->name, $this->key, $this->cert, $this->ca);
        }
        else
        {
            $this->mysqlRef = $this->mysqlConnections->getConnection($this, $this->host, $this->user, $this->password, $this->name, $this->key, $this->cert, $this->ca);
        }
    }

    /**
     * Get if mysqltcs is connected (using mysqli::ping)
     * @return bool if true mysqltcs is connected
     */
    public function isConnected(){
        return $this->mysqlRef->getMysqli()->ping();
    }

    /**
     * Get the thread id (it can be used as mysqli identifier)
     * @return int
     */
    public function getConnectionThreadId(){
        return $this->mysqlRef->getMysqli()->thread_id;
    }

}