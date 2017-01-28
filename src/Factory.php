<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Novactive\Collection;

use Traversable;

/**
 * Class Factory.
 */
class Factory
{
    /**
     * @param array $items
     * @param       $mode
     *
     * @return Collection
     */
    public static function create($items = [], $className = 'Novactive\Collection\Collection')
    {
        $options = null;
        if (getenv('DEBUG_COLLECTION_CLASS') && getenv('DEBUG_COLLECTION_CLASS') != '') {
            $className = getenv('DEBUG_COLLECTION_CLASS');
        }

        return new $className(static::getArrayForItems($items), $options);
    }

    /**
     * @param $items
     *
     * @return array
     */
    public static function getArrayForItems($items)
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof Collection) {
            return $items->toArray();
        } elseif ($items instanceof Traversable) {
            return iterator_to_array($items);
        }

        return (array)$items;
    }
}
