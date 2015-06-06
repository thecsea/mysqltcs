<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 06/06/15
 * Time: 17.36
 */

namespace it\thecsea\mysqltcs;

/**
 * Class SimpleLogger
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 ClaudioCardinale
 * @version 3.0.0-dev
 * @package it\thecsea\mysqltcs
 */
class SimpleLogger implements MysqltcsLogger
{
    /**
     * @var String[]
     */
    private $logArray = Array();

    /**
     * Log a message
     * @param String $mex
     */
    public function log($mex)
    {
        $this->logArray[] = $mex;
    }

    /**
     * @return String
     */
    public function getLogArray()
    {
        return $this->logArray;
    }
}