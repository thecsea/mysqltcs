<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 22/05/15
 * Time: 22.51
 */

namespace it\thecsea\mysqlTCS\connections\utilis;


use it\thecsea\mysqlTCS\connections\MysqlConnectionException;

/**
 * Class MysqlUtilisException
 * @package it\thecsea\mysqlTCS\connections\utilis
 */
class MysqlUtilisException extends MysqlConnectionException
{
}


/**
 * Class MysqlUtilis
 * @package it\thecsea\mysqlTCS\connections\utilis
 */
class MysqlUtilis
{
    /**
     * Init a mysqli connection over SSL
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $name
     * @param string $key
     * @param string $cert
     * @param string $ca
     * @return \mysqli
     * @throws MysqlUtilisException
     */
    static public function sslConnect($host, $user, $password, $name, $key, $cert, $ca)
    {
        if ($key == "" || $cert == "" || $ca == "") {
            throw new MysqlUtilisException("SSL parameters error");
        }

        $mysqliRef = mysqli_init();
        $mysqliRef->ssl_set($key, $cert, $ca, null, null);

        $connected = @$mysqliRef->real_connect($host,
            $user,
            $password,
            $name,
            3306,
            null,
            MYSQLI_CLIENT_SSL);

        if (!$connected || $mysqliRef->connect_error)
            throw new MysqlUtilisException('Database connection error (' . $mysqliRef->connect_errno . ') '. $mysqliRef->connect_error);

        return $mysqliRef;
    }


    /**
     * Init a normal mysqli connection
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $name
     * @return \mysqli
     * @throws MysqlUtilisException
     */
    static public function connect($host, $user, $password, $name)
    {
        $mysqliRef = mysqli_init();

        $connected = @$mysqliRef->real_connect($host,
            $user,
            $password,
            $name);

        if (!$connected || $mysqliRef->connect_error)
            throw new MysqlUtilisException('Database connection error (' . $mysqliRef->connect_errno . ') '. $mysqliRef->connect_error);

        return $mysqliRef;
    }

}