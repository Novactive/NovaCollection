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

use InvalidArgumentException;
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
    public static function create($items = [], $className = null)
    {
        if (!is_array($items) && !($items instanceof Traversable)) {
            throw new InvalidArgumentException('Invalid input type for '.__METHOD__.', cannot create factory.');
        }

        if ($className !== null) {
            return static::fill(new $className(), $items);
        }

        if (defined('DEBUG_COLLECTION') && DEBUG_COLLECTION == true) {
            return static::fill(new DebugCollection(), $items);
        }

        if (getenv('DEBUG_COLLECTION_CLASS') && getenv('DEBUG_COLLECTION_CLASS') != '') {
            $className = getenv('DEBUG_COLLECTION_CLASS');

            return static::fill(new $className(), $items);
        }

        return static::fill(new Collection(), $items);
    }

    /**
     * @param Collection $collection
     * @param            $items
     *
     * @return Collection
     */
    protected static function fill(Collection $collection, $items)
    {
        //@todo: should be removed, we need something like
        // https://github.com/illuminate/support/blob/master/Collection.php#L1425
        // => direclty in the constructor
        foreach ($items as $key => $val) {
            $collection->set($key, $val);
        }

        return $collection;
    }
}
