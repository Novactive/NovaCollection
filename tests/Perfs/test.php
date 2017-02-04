<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
use Novactive\Collection\Collection;
use Novactive\Tests\Perfs\ArrayMethodCollection;
use Novactive\Tests\Perfs\ForeachMethodCollection;

include __DIR__.'/../bootstrap.php';
ini_set('memory_limit', '8500M');

$method         = (string)$_SERVER['argv'][1];
$collectionType = (int)$_SERVER['argv'][2];
$iterations     = (int)$_SERVER['argv'][3];
$jsonMode       = (bool)$_SERVER['argv'][4];

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

if ($method == 'filter') {
    $fn = function ($item) {
        return $item % 2;
    };
    $collection->filter($fn);
}
if ($method == 'map') {
    $fn = function ($item) {
        return $item.'nova';
    };
    $collection->map($fn);
}
if ($method == 'each') {
    $fn = function ($item) {
        $item .= 'nova';
    };
    $collection->each($fn);
}
if ($method == 'combine') {
    $collection->combine($data2);
}

if ($method == 'reduce') {
    $fn = function ($item) {
        $item .= 'nova';

        return $item;
    };
    $collection->reduce($fn, 'plop');
}

if ($method == 'contains') {
    $collection->contains('plop');
}
if ($method == 'merge') {
    $collection->merge($data2);
}

if ($method == 'union') {
    $collection->union($data2);
}

if (in_array($method, ['flip', 'values', 'keys', 'unique', 'reverse', 'shift', 'pop'])) {
    $collection->$method();
}

if ($method == 'chunk') {
    $collection->chunk(ceil($iterations / 5) + 1);
}

if ($method == 'slice') {
    $collection->slice(ceil($iterations / 2), ceil($iterations / 4));
    $collection->slice(ceil($iterations / 2) * -1);
}

if ($method == 'diff') {
    $collection->diff(range(0, $iterations));
}
if ($method == 'intersect') {
    $collection->intersect(range(0, $iterations));
}

if ($method == 'diffKeys') {
    $collection->combine($data2);
    $collection->diff(range(0, $iterations));
}
if ($method == 'intersectKeys') {
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
            'time'       => (string)$time,
        ]
    );
}
echo PHP_EOL;
