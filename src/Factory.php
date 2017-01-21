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
     * @param mixed $items
     *
     * @return Collection
     */
    public static function create($items = [])
    {
        if (!is_array($items) && !($items instanceof Traversable)) {
            throw new InvalidArgumentException('Invalid input type for '.__METHOD__.', cannot create factory.');
        }
        $collection = new Collection();
        foreach ($items as $key => $val) {
            $collection->set($key, $val);
        }

        return $collection;
    }
}
