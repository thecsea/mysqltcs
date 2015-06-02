<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 22/05/15
 * Time: 22.14
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


use it\thecsea\mysqlTCS\connections\utilis\MysqlUtilis;

/**
 * Class MysqlConnection
 * @author      Claudio Cardinale <cardi@thecsea.it>
 * @version     3.0-dev
 * @package it\thecsea\mysqlTCS\connections
 */
class MysqlConnection {
    /**
     * @var \mysqli
     */
    private $mysqlRef;

    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $name;
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
     * Get a connection to mysql
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $name
     * @param string $key optional
     * @param string $cert optional
     * @param string $ca optional
     */
    public function __construct($host, $user, $password, $name, $key = "", $cert = "", $ca = "")
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->name = $name;
        $this->key = $key;
        $this->cert = $cert;
        $this->ca = $ca;
        $this->getConnection();
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->mysqlRef->close();
    }

    /**
     * @throws utilis\MysqlUtilisException
     */
    private function getConnection(){
        if($this->key != "" && $this->cert != "" && $this->ca != ""){
            $this->mysqlRef =  MysqlUtilis::sslConnect($this->host, $this->user, $this->password, $this->name, $this->key, $this->cert, $this->ca);
        }else{
            $this->mysqlRef =  MysqlUtilis::Connect($this->host, $this->user, $this->password, $this->name);
        }
    }

    /**
     * @return \mysqli
     */
    public function getMysqli()
    {
        return $this->mysqlRef;
    }

    /**
     * Check if passed properties are equals to instance properties
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $name
     * @param string $key
     * @param string $cert
     * @param string $ca
     * @return bool true if properties are equals
     */
    public function equalsProperties($host, $user, $password, $name, $key = "", $cert = "", $ca = "")
    {
        return ($this->host == $host && $this->user == $user && $this->password == $password && $this->name == $name &&$this->key == $key && $this->cert = $cert && $this->ca = $ca);
    }
}