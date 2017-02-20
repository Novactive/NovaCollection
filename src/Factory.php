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
     * @param Traversable|array $items
     * @param string            $className
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
        if ($items instanceof Collection) {
            return $items->toArray();
        } elseif (is_array($items)) {
            return $items;
        } elseif ($items instanceof Traversable) {
            return iterator_to_array($items);
        } elseif (is_string($items)) {
            $json = json_decode($items, true);
            if (is_array($json) && json_last_error() == JSON_ERROR_NONE) {
                return $json;
            }
        }

        return (array)$items;
    }
}
