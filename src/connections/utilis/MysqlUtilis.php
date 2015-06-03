<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 22/05/15
 * Time: 22.51
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

namespace it\thecsea\mysqlTCS\connections\utilis;


use it\thecsea\mysqlTCS\connections\MysqlConnectionException;

/**
 * Class MysqlUtilisException
 * @author      Claudio Cardinale <cardi@thecsea.it>
 * @copyright   2015 claudio cardinale
 * @version     3.0-dev
 * @package it\thecsea\mysqlTCS\connections\utilis
 */
class MysqlUtilisException extends MysqlConnectionException
{
}


/**
 * Class MysqlUtilis
 * @author      Claudio Cardinale <cardi@thecsea.it>
 * @copyright   2015 claudio cardinale
 * @version     3.0-dev
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