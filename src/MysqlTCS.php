<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 22/05/15
 * Time: 21.48
 */

namespace it\thecsea\mysqlTCS;


/**
 * Class MysqlTCS
 * @package it\thecsea\mysqlTCS
 */
class MysqlTCS {

    private $host;
    private $user;
    private $password;
    private $name;
    private $newConnection;
    private $key;
    private $cert;
    private $ca;
    private $db;

    /**
     * Get a connection to mysql
     * @param $host
     * @param $user
     * @param $password
     * @param $name
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
        getConnection();
    }


    private function getConnection()
    {
        //TODO implement...
    }

}