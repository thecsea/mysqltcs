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

namespace it\thecsea\mysqlTCS;
use it\thecsea\mysqlTCS\connections\MysqlConnections;
use it\thecsea\mysqlTCS\connections\MysqlConnection;
use it\thecsea\mysqlTCS\connections\MysqlConnectionsException;


/**
 * Class MysqlTCS
 * @author      Claudio Cardinale <cardi@thecsea.it>
 * @version     3.0-dev
 * @package it\thecsea\mysqlTCS
 */
class MysqlTCS {

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
     * @var \mysqli
     */
    private $mysqlRef;

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
        $this->getConnection();
    }

    /**
     * @throws MysqlConnectionException
     */
    public function __destruct()
    {
        if(!$this->newConnection)
            MysqlConnections::removeClient($this);

    }

    /**
     * Get the connection according to newConnection value
     * @throws MysqlConnectionException
     */
    private function getConnection()
    {
        if($this->newConnection)
        {
            $tmp = new MysqlConnection($this->host, $this->user, $this->password, $this->name, $this->key, $this->cert, $this->ca);
            $this->mysqlRef = $tmp->getMysqli();
        }
        else
        {
            $this->mysqlRef = MysqlConnections::getConnection($this, $this->host, $this->user, $this->password, $this->name, $this->key, $this->cert, $this->ca);
        }
    }

}