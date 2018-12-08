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

use Novactive\Collection\Collection;
use Novactive\Tests\Perfs\ArrayMethodCollection;
use Novactive\Tests\Perfs\ForeachMethodCollection;

include __DIR__.'/../bootstrap.php';
ini_set('memory_limit', '8500M');

$method         = (string) $_SERVER['argv'][1];
$collectionType = (int) $_SERVER['argv'][2];
$iterations     = (int) $_SERVER['argv'][3];
$jsonMode       = (bool) $_SERVER['argv'][4];

$data  = range(0, $iterations - 1);
$data2 = range(0, $iterations - 1);
shuffle($data);
shuffle($data2);

// if we want with text instead of integer but it does not change anything
$textitize = function ($value) {
    return md5($value);
};
//$data  = array_map($textitize,$data);
//$data2 = array_map($textitize,$data2);

switch ($collectionType) {
    case 0:
        $collection  = new Collection($data);
        $collection2 = new Collection($data2);
        break;
    case 1:
        $collection  = new ArrayMethodCollection($data);
        $collection2 = new ArrayMethodCollection($data2);
        break;
    case 2:
        $collection  = new ForeachMethodCollection($data);
        $collection2 = new ForeachMethodCollection($data2);
        break;
}

$start = microtime(true);

if ('filter' === $method) {
    $fn = function ($item) {
        return $item % 2;
    };
    $collection->filter($fn);
}
if ('map' === $method) {
    $fn = function ($item) {
        return $item.'nova';
    };
    $collection->map($fn);
}
if ('each' === $method) {
    $fn = function ($item) {
        $item .= 'nova';
    };
    $collection->each($fn);
}
if ('combine' === $method) {
    $collection->combine($data2);
}

if ('reduce' === $method) {
    $fn = function ($item) {
        $item .= 'nova';

        return $item;
    };
    $collection->reduce($fn, 'plop');
}

if ('contains' === $method) {
    $collection->contains('plop');
}
if ('merge' === $method) {
    $collection->merge($data2);
}

if ('union' === $method) {
    $collection->union($data2);
}

if (in_array($method, ['flip', 'values', 'keys', 'unique', 'reverse', 'shift', 'pop'])) {
    $collection->$method();
}

if ('chunk' === $method) {
    $collection->chunk((int) ceil($iterations / 5) + 1);
}

if ('slice' === $method) {
    $collection->slice((int) ceil($iterations / 2), (int) ceil($iterations / 4));
    $collection->slice((int) ceil($iterations / 2) * -1);
}

if ('diff' === $method) {
    $collection->diff(range(0, $iterations));
}
if ('intersect' === $method) {
    $collection->intersect(range(0, $iterations));
}

if ('diffKeys' === $method) {
    $collection->combine($data2);
    $collection->diff(range(0, $iterations));
}
if ('intersectKeys' === $method) {
    $collection->combine($data2);
    $collection->intersect(range(0, $iterations));
}

$end  = microtime(true);
$time = $end - $start;

if (!$jsonMode) {
    echo get_class($collection)." {$method} on {$iterations}: ".$time;
} else {
    echo
    json_encode(
        [
            'type'       => get_class($collection),
            'iterations' => $iterations,
            'method'     => $method,
            'time'       => (string) $time,
        ]
    );
}
echo PHP_EOL;
