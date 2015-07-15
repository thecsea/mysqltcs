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
     * if true the logger print the mex when log() si called
     * @var bool
     */
    private $print = false;

    /**
     * Log a message
     * @param String $mex
     */
    public function log($mex)
    {
        if($this->print)
            print $mex."\n";
        $this->logArray[] = $mex;
    }

    /**
     * return print status
     * @return boolean
     */
    public function isPrint()
    {
        return $this->print;
    }

    /**
     * set print, if true the logger print the mex when log() si called
     * @param boolean $print
     */
    public function setPrint($print)
    {
        $this->print = $print;
    }

    /**
     * @return String
     */
    public function getLogArray()
    {
        return $this->logArray;
    }
}