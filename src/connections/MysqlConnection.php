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

namespace it\thecsea\mysqltcs\connections;


use it\thecsea\mysqltcs\connections\utilis\MysqlUtilis;

/**
 * Class MysqlConnection
 * this class manage a single mysql connection calling the php mysql functions
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 claudio cardinale
 * @version 3.0.0-dev
 * @package     it\thecsea\mysqltcs\connections
 */
class MysqlConnection {
    /**
     * @var int
     */
    private $instanceNumber;
    /**
     * @var int
     */
    static private $instances = 0;
    /**
     * @var \mysqli
     */
    private $mysqlRef = null;
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
        $this->instanceNumber = ++self::$instances;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->name = $name;
        $this->key = $key;
        $this->cert = $cert;
        $this->ca = $ca;
    }

    /**
     * @throws utilis\MysqlUtilisException
     */
    public function __clone()
    {
        //increment instance number to distinguish the classes, so each $this points to a different instance
        $this->instanceNumber = ++self::$instances;
        if ($this->mysqlRef != null)
            $this->connect();
    }

    /**
     * @return int
     */
    public function getInstanceNumber()
    {
        return $this->instanceNumber;
    }

    /**
     * @param int $instanceNumber
     */
    public function setInstanceNumber($instanceNumber)
    {
        $this->instanceNumber = $instanceNumber;
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->mysqlRef) {
                    $this->mysqlRef->close();
        }
    }

    /**
     * @throws utilis\MysqlUtilisException
     */
    public function connect() {
        if ($this->key != "" && $this->cert != "" && $this->ca != "") {
            $this->mysqlRef = MysqlUtilis::sslConnect($this->host, $this->user, $this->password, $this->name, $this->key, $this->cert, $this->ca);
        } else {
            $this->mysqlRef = MysqlUtilis::Connect($this->host, $this->user, $this->password, $this->name);
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
     * Check if the passed properties are equal to instance properties
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
        return ($this->host == $host && $this->user == $user && $this->password == $password && $this->name == $name && $this->key == $key && $this->cert == $cert && $this->ca == $ca);
    }

    /**
     * Check if the passed object is equal to this, this check the properties not the mysqli connection
     * @param MysqlConnection $connection
     * @return bool true if object is equal
     */
    public function equals(MysqlConnection $connection) {
        return ($this->host == $connection->host && $this->user == $connection->user && $this->password == $connection->password && $this->name == $connection->name && $this->key == $connection->key && $this->cert == $connection->cert && $this->ca == $connection->ca);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ("instance number: ".$this->instanceNumber."\nhost: ".$this->host."\nuser: ".$this->user."\npassword: ".$this->password."\nname: ".$this->name."\nkey: ".$this->key."\ncert: ".$this->cert."\nca: ".$this->ca);
    }
}