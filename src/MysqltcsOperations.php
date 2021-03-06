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
 * @copyright 2015 Claudio Cardinale
 * @version 3.0.4
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
     * a table or a more complex type like a subquery, if you want use complex data set quotes to false.
     * Obviously you can use complex constructs like JOIN
     * @param bool|true $defaultQuotes if true ` are inserted at the start and at the end of $from. it suggested to keep this
     * true if you use only one table in from label
     * @throws MysqltcsException
     */
    public function __construct(Mysqltcs $mysqltcs, $defaultFrom = "", $defaultQuotes = true)
    {
        $this->mysqltcs = $mysqltcs;
        $this->from = $defaultFrom;
        $this->quotes = $defaultQuotes;

        self::mysqltcsCheck($mysqltcs);
    }

    /**
     * throw exception if mysqltcs passed is not valid
     * @param Mysqltcs $mysqltcs
     * @throws MysqltcsException
     */
    private static function mysqltcsCheck(Mysqltcs $mysqltcs)
    {
        if($mysqltcs == null || !($mysqltcs instanceof Mysqltcs)) {
            throw new MysqltcsException("mysqltcs passed is not an instance of Mysqltcs");
        }

        if (!$mysqltcs->isConnected()) {
            throw new MysqltcsException("mysqltcs passed is not connected");
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ("from: " . $this->from . "\nquotes: " . ($this->quotes?"true":false) . "\nmysqltcs:\n" . (string)$this->mysqltcs);
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
     * @throws MysqltcsException
     */
    public function setMysqltcs(Mysqltcs $mysqltcs)
    {
        $this->mysqltcs = $mysqltcs;

        self::mysqltcsCheck($mysqltcs);
    }

    /**
     * @return String
     */
    public function getDefaultFrom()
    {
        return $this->from;
    }

    /**
     * @param String $from
     */
    public function setDefaultFrom($from)
    {
        $this->from = $from;
    }


    /**
     * @return bool
     */
    public function isDefaultQuotes()
    {
        return $this->quotes;
    }

    /**
     * @param bool $quotes
     */
    public function setDefaultQuotes($quotes)
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
     * @throws MysqltcsException thrown on mysql error, for example invalid data or permission denied
     */
    public function showTables()
    {
        return $this->getSimpleList("SHOW TABLES;");
    }

    /**
     * return all databases names of the current server
     * @return array
     * @throws MysqltcsException thrown on mysql error, for example invalid data or permission denied
     */
    public function showDatabases()
    {
        return $this->getSimpleList("SHOW DATABASES;");
    }

    /**
     * return an array of values of each row of element in the $pos column
     * @param String $query
     * @param int $pos column number
     * @return array
     * @throws MysqltcsException thrown on mysql error, for example invalid data or permission denied
     */
    private function getSimpleList($query, $pos = 0)
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
     * @param string $from you have to set to a table. If you leave this empty default value is used
     * @return string|null
     * @throws MysqltcsException thrown on mysql error, for example invalid data or permission denied
     */
    public function getTableInfo($returnName, $from = "")
    {
        $from = $this->fromCheck($from, false);

        //if an error is occurred mysqltcs throw an exception
        $results = $this->mysqltcs->executeQuery("show table status like '$from';");

        $ret = null;
        if (($row = $results->fetch_array()) !== false && isset($row[$returnName])) {
            $ret = $row[$returnName];
        }

        //free memory
        $results->free();

        return $ret;
    }

    /**
     * Return the formatted string for a multiple insert
     * @param array|string $array it can be a string array of multiple insert or a string of a single insert,
     * In any case the String must be the SQL single insert string
     * @param bool|false $newline if true each insert is separated by \n
     * @return string formatted string
     */
    private static function insertValues($array, $newline = false)
    {
        return ("(" . implode(")," . ($newline ? "\n" : "") . "(", (array)$array) . ")");
    }

    /**
     * Insert a row or multiple rows
     * @param string $fields SQL list of flieds, you can even use subquery or "as" (SQL).
     * The simplest example is: \`field1\`, \`field2\`, ...
     * @param array|string $values you can use this parameter in two different way:
     * <ul>
     * <li>you can pass a string array of rows
     * <li>you can pass a string of a single row
     * </ul>
     * in any case the string(each element of array) must be composed by an SQL string well formatted, for example:
     * \'data1\', \'data2\' <br>
     * N.B. if you want pass an array you have to pass an array like this:
     * array("\'data11\', \'data12\'", \'data21\', \'data22\')<br>
     * <b>CAUTION</b>: you have to insert \' delimiters for varchar or text field, but not for numerical fields.
     * @param string $from you can use this field to specify the INTO value, if you leave this empty,
     * default value is used ($defaultFrom)
     * @throws MysqltcsException thrown on mysql error, for example invalid data or permission denied
     */
    public function insert($fields, $values, $from = "")
    {
        $from = $this->fromCheck($from, true);

        //if an error is occurred mysqltcs throw an exception
        $this->mysqltcs->executeQuery("INSERT INTO $from ($fields) VALUES " . self::insertValues($values) . ";");
    }

    /**
     * Get the first value of $field based on $where.<br>
     * This method does not make any type of order, so you are not able to know which value is taken
     * if there are more values under $where condition, so we suggest to use this method only with conditions
     * that generates only one value. If you have more values under condition we suggest to use getList method
     * @param string $field filed name
     * @param string $where the where condition to get the value,obviously you can use different tables
     * (via chain form: e.g. tableName.id)
     * @param string $from you can use this field to specify the from value, if you leave this empty,
     * default value is used. Obviously you can use complex constructs like JOIN
     * @param bool|null $quotes you can use this field to specify if insert \` at limits of $from or not.
     * If you leave this empty,default value is used
     * @return string|null the value
     * @throws MysqltcsException thrown on mysql error, for example invalid data or permission denied
     */
    public function getValue($field, $where, $from = "", $quotes = null)
    {
        $from = $this->fromCheck($from, $quotes);

        //if an error is occurred mysqltcs throw an exception
        $results = $this->mysqltcs->executeQuery("SELECT $field FROM $from WHERE $where;");

        if (($row = $results->fetch_array()) !== false) {
            //free memory
            $results->free();
            //check for chain, for example: db.table.field
            $field = explode(".", $field);
            $field = $field[count($field) - 1];
            //return value
            return $row[$field];
        }

        //no data
        return null;
    }

    /**
     * This method make a select and return the results as a associative matrix, you can make a simple select in one or
     * in more tables, you can make a select with order or not. This is a less powerful method than getListAdvanced
     * @param string $select the select SQL field. Obviously you can use different tables
     * (via chain form: e.g. tableName.id),you can also use SQL construct like 'as'
     * @param string $where the where condition to get the values,obviously you can use different tables
     * (via chain form: e.g. tableName.id)
     * @param string $order you can insert the order SQL rules, if you leave this empty the query is executed without
     * any order. Order example: 'id ASC'
     * @param string $from you can use this field to specify the from value, if you leave this empty,
     * default value is used. Obviously you can use complex constructs like JOIN
     * @param bool|null $quotes you can use this field to specify if insert \` at limits of $from or not.
     * If you leave this empty,default value is used
     * @return array the associative matrix of results
     * @throws MysqltcsException thrown on mysql error, for example invalid data or permission denied
     */
    public function getList($select, $where, $order = "", $from = "", $quotes = null)
    {
        if ($order)
            return $this->getListAdvanced($select, $where, " ORDER BY " . $order, $from, $quotes);
        else
            return $this->getListAdvanced($select, $where, $order, $from, $quotes);
    }

    /**
     *
     * This method make a select and return the results as a associative matrix. This is method is more powerful than
     * getList, in fact it allows to specify all advanced SQL constructs, not only order, but even for example group by
     * and having
     * @param string $select the select SQL field. Obviously you can use different tables
     * (via chain form: e.g. tableName.id), you can also use SQL construct like 'as'
     * @param string $where the where condition to get the values,obviously you can use different tables
     * (via chain form: e.g. tableName.id)
     * @param string $other you can insert what you want (obviously it must be SQL)
     * @param string $from you can use this field to specify the from value, if you leave this empty,
     * default value is used. Obviously you can use complex constructs like JOIN
     * @param bool|null $quotes you can use this field to specify if insert \` at limits of $from or not.
     * If you leave this empty,default value is used
     * @return array the associative matrix of results
     * @throws MysqltcsException thrown on mysql error, for example invalid data or permission denied
     */
    public function getListAdvanced($select, $where, $other = "", $from = "", $quotes = null)
    {
        $from = $this->fromCheck($from, $quotes);

        //if an error is occurred mysqltcs throw an exception
        $results = $this->mysqltcs->executeQuery("SELECT $select FROM $from WHERE $where$other;");
        $ret = array();
        $i = 0;
        while ($row = $results->fetch_array()) {
            $j = 0;
            foreach ($row as $key => $value) {
                //get only element with associative key
                if ($j++ % 2) {
                    $ret[$i][$key] = $value;
                }
            }
            $i++;
        }

        //free memory
        $results->free();

        return $ret;
    }

    /**
     * Delete one or more rows according to $where condition
     * @param string $where the where condition to delete values,obviously you can use different tables
     * (via chain form: e.g. tableName.id)
     * @param string $from you can use this field to specify the from value, if you leave this empty,
     * default value is used. Obviously you can use complex constructs like JOIN
     * @param bool|null $quotes you can use this field to specify if insert \` at limits of $from or not.
     * If you leave this empty,default value is used
     * @return int the deleted rows number
     * @throws MysqltcsException thrown on mysql error, for example invalid data or permission denied
     */
    public function deleteRows($where, $from = "", $quotes = null)
    {
        $from = $this->fromCheck($from, $quotes);

        //if an error is occurred mysqltcs throw an exception
        $this->mysqltcs->executeQuery("DELETE FROM $from WHERE $where;");

        return $this->mysqltcs->getAffectedRows();
    }

    /**
     * Check $from, it returns the correct from (default or passed with/without quotes)
     * @param string $from
     * @param bool|null $quotes
     * @return string new from
     */
    private function fromCheck($from, $quotes = null)
    {
        if ($from == "") {
            $from = $this->from;
        }
        if ($quotes === null) {
            $quotes = $this->quotes;
        }
        if ($quotes) {
            $from = "`" . $from . "`";
        }

        return $from;
    }

    /**
     * make an SQL update
     * @param array $values associative matrix of new values, the key must be the field name.
     * For example: array("id"=>"5", "value"="'test3'"). <br>
     * <b>CAUTION</b>: you have to insert \' delimiters for varchar or text field, but not for numerical fields<br>
     * Obviously you can use different tables (via chain form: e.g. tableName.id)
     * @param string $where the where condition to update values, obviously you can use different tables
     * (via chain form: e.g. tableName.id)
     * @param string $from you can use this field to specify the from value, if you leave this empty,
     * default value is used. Obviously you can use complex constructs like JOIN
     * @param bool|null $quotes you can use this field to specify if insert \` at limits of $from or not.
     * If you leave this empty,default value is used
     * @return int the updated rows number
     * @throws MysqltcsException thrown on mysql error, for example invalid data or permission denied
     */
    public function update(array $values, $where, $from = "", $quotes = null)
    {
        $from = $this->fromCheck($from, $quotes);
        $set = self::updateSet($values);

        //if an error is occurred mysqltcs throw an exception
        $this->mysqltcs->executeQuery("UPDATE $from SET $set WHERE $where;");

        return $this->mysqltcs->getAffectedRows();
    }

    /**
     * return the SQL string for set
     * @param array $values associative array,the key must be the SQL field name
     * @return string set
     */
    private static function updateSet(array $values)
    {
        $ret = "";
        foreach($values as $key=>$value)
            $ret .= $key."=".$value.", ";
        $ret = substr($ret,0,-2);//remove last \", \"
        return $ret;
    }

    /**
     * return the escaped string
     * @param $string
     * @return string
     */
    public function getEscapedString($string)
    {
        return $this->mysqltcs->getEscapedString($string);
    }
}