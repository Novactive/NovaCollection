<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 *
 * @copyright 2017 Novactive
 * @license   MIT
 */
namespace Novactive\Collection;

/**
 * Class Factory
 */
class Factory
{
    /**
     * @param array $items
     *
     * @return Collection
     */
    public static function create(array $items = [])
    {
        return new Collection($items);
    }
}
