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

namespace Novactive\Collection;

class Factory
{
    public static function create($items = [], string $className = Collection::class): Collection
    {
        if (getenv('DEBUG_COLLECTION_CLASS') && '' != getenv('DEBUG_COLLECTION_CLASS')) {
            $className = (string) getenv('DEBUG_COLLECTION_CLASS');
        }

        return new $className(static::getArrayForItems($items));
    }

    /**
     * @param $items
     */
    public static function getArrayForItems($items): array
    {
        if ($items instanceof Collection) {
            return $items->toArray();
        }

        if (\is_array($items)) {
            return $items;
        }

        if ($items instanceof \Traversable) {
            return iterator_to_array($items);
        }

        if (\is_string($items)) {
            $json = json_decode($items, true);
            if (\is_array($json) && JSON_ERROR_NONE == json_last_error()) {
                return $json;
            }
        }

        return (array) $items;
    }
}
