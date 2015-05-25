<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 22/05/15
 * Time: 22.14
 */

namespace it\thecsea\mysqlTCS\connections;


use it\thecsea\mysqlTCS\connections\utilis\MysqlUtilis;

/**
 * Class MysqlConnection
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