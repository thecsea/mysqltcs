<?php
/**
     * Created by PhpStorm.
     * User: claudio
     * Date: 11/07/15
     * Time: 17.53
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


/**
 * Class MysqltcsOperations
 * This class allow you to perform efficiently common tasks like a simple insert
 * you have to instance this passing a Mysqltcs connection instantiated before
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 ClaudioCardinale
 * @version 3.0.0-dev
 * @package it\thecsea\mysqltcs
 */
class MysqltcsOperations
{
    /**
     * @var Mysqltcs
     */
    private $mysqltcs;
    /**
     * @var String
     */
    private $from;
    /**
     * @var bool
     */
    private $quotes;


    /**
     * Instance the class based on the mysqltcs connections and $defaultFrom and $defaultQuotes value that
     *  are used as default values in all method, but you can set local value for every method
     * @param Mysqltcs $mysqltcs mysqltcs connected to a valid databases
     * @param string $defaultFrom value of from mysql from label. It can assume all data types, for example it can be simply
     * a table or a more complex type like a subquery, if you want use complex data set quotes to false
     * @param bool|true $defaultQuotes if true ` are inserted at the start and at the end of $from. it suggested to keep this
     * true if you use only one table in from label
     * @throws MysqltcsException
     */
    function __construct(Mysqltcs $mysqltcs, $defaultFrom = "", $defaultQuotes = true)
    {
        $this->mysqltcs = $mysqltcs;
        $this->from = $defaultFrom;
        $this->quotes = $defaultQuotes;
        if (!$mysqltcs->isConnected()) {
                    throw new MysqltcsException("mysqltcs passed is not connected");
        }
    }

    /**
     * @return string
     */
    function __toString()
    {
        return ("from: ".$this->from."\nquotes: ".($this->quotes ? "true" : "false")."\nmysqltcs:\n".(string) $this->mysqltcs);
    }

    /**
     * This entails that you can clone every instance of this class
     */
    public function __clone()
    {
    }

    /**
     * @return Mysqltcs
     */
    public function getMysqltcs()
    {
        return $this->mysqltcs;
    }

    /**
     * @param Mysqltcs $mysqltcs
     */
    public function setMysqltcs($mysqltcs)
    {
        $this->mysqltcs = $mysqltcs;
    }

    /**
     * @return String
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param String $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }


    /**
     * @return boolean
     */
    public function isQuotes()
    {
        return $this->quotes;
    }

    /**
     * @param boolean $quotes
     */
    public function setQuotes($quotes)
    {
        $this->quotes = $quotes;
    }

    /**
     * get the id of the last element inserted
     * @return mixed
     */
    public function getLastId()
    {
        return $this->mysqltcs->getLastId();
    }

    /**
     * return all tables names of the current db
     * @return array
     * @throws MysqltcsException
     */
    public function showTables()
    {
        return $this->simpleList("SHOW TABLES;");
    }

    /**
     * return all databases names of the current server
     * @return array
     * @throws MysqltcsException
     */
    public function showDatabases()
    {
        return $this->simpleList("SHOW DATABASES;");
    }

    /**
     * return an array of values of each row of element in the $pos column
     * @param String $query
     * @param int $pos column number
     * @return array
     * @throws MysqltcsException
     */
    private function simpleList($query, $pos = 0)
    {
        //if an error is occurred mysqltcs throw an exception
        $results = $this->mysqltcs->executeQuery($query);
        $ret = array();

        //insert results in an array
        $i = 0;
        while ($row = $results->fetch_array()) {
                    $ret[$i++] = $row[$pos];
        }


        //free memory
        $results->free();

        return $ret;
    }

    /**
     * Return $returnName value info of table indicated in $from
     * @param $returnName
     * @param string $from you have to set to a table
     * @return String|null
     * @throws MysqltcsException
     */
    public function tableInfo($returnName, $from = "")
    {
        if ($from == "") {
                    $from = $this->from;
        }

        //if an error is occurred mysqltcs throw an exception
        $results = $this->mysqltcs->executeQuery("show table status like '$from';");

        $ret = null;
        if (($row = $results->fetch_array()) !== false) {
                    $ret = isset($row[$returnName]) ? $row[$returnName] : null;
        }

        //free memory
        $results->free();

        return $ret;
    }
}