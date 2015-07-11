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
     * @param Mysqltcs $mysqltcs mysqltcs connected to a valid databases
     * @param string $from value of from mysql from label. It can assume all data types, for example it can be simply a table or a more complex type like a subquery, if you want use complex data set quotes to false
     * @param bool|true $quotes if true ` are inserted at the start and at the end of $from. it suggested to keep this true if you use only one table in from label
     */
    function __construct(Mysqltcs $mysqltcs, $from = "", $quotes = true)
    {
        $this->mysqltcs = $mysqltcs;
        $this->from = $from;
        $this->quotes = $quotes;
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
}