<?php
/**
 * Novactive Collection Test Bootstrap.
 *
 * Place autoloader, debugging functions, or PHPUnit customizations in this file.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 *
 * @copyright 2017 Novactive
 * @license   MIT
 */

require_once __DIR__  . '/../vendor/autoload.php';

use Symfony\Component\VarDumper\VarDumper;

// just a debugging function I use while testing...
if (!function_exists('dd')) {
    function dd($val, $die = true)
    {
        VarDumper::dump($val);
        if ($die) {
            exit;
        }
    }
}