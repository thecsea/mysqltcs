<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03/06/15
 * Time: 23.08
 */

namespace it\thecsea\mysqltcs;


/**
 * Interface MysqltcsLogger
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 Claudio Cardinale
 * @version 3.0.0
 * @package it\thecsea\mysqltcs
 */
interface MysqltcsLogger {
    /**
     * Log a message
     * @param String $mex
     */
    public function log($mex);
}