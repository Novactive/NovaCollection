<?php
/**
 * Novactive Collection Test Bootstrap.
 *
 * Place autoloader, debugging functions, or PHPUnit customizations in this file.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
require_once __DIR__.'/../vendor/autoload.php';
error_reporting(E_ERROR | E_STRICT | E_NOTICE);
ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');
