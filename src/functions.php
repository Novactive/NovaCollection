<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
declare(strict_types=1);

use Novactive\Collection\Factory;

if (!function_exists('Collection')) {
    function Collection($items)
    {
        return Factory::create($items);
    }
}

if (!function_exists('NovaCollection')) {
    function NovaCollection($items)
    {
        return Factory::create($items);
    }
}
