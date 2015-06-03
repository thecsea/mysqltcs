<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03/06/15
 * Time: 23.08
 */

namespace it\thecsea\mysqltcs;


/**
 * Interface mysqltcsLogger
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 ClaudioCardinale
 * @version 3.0.0-dev
 * @package it\thecsea\mysqltcs
 */
interface mysqltcsLogger {
    /**
     * Log a message
     * @param String $mex
     */
    public function log($mex);
}