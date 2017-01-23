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
use Novactive\Tests\Perfs\NativeMethodCollection;

include __DIR__.'/../bootstrap.php';
ini_set('memory_limit', '8500M');

$method          = (string)$_SERVER['argv'][1];
$usingCollection = (bool)$_SERVER['argv'][2];
$iterations      = (int)$_SERVER['argv'][3];
$jsonMode        = (bool)$_SERVER['argv'][4];

$data  = range(0, $iterations);
$data2 = range(0, $iterations);
shuffle($data);
shuffle($data2);

$collection = $usingCollection ? new Collection($data) : new NativeMethodCollection($data);

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
$end  = microtime(true);
$time = $end - $start;

if (!$jsonMode) {
    echo($usingCollection ? 'NovaC' : 'Array')." {$method} on {$iterations}: ".$time;
} else {
    echo
    json_encode(
        [
            'type'       => $usingCollection ? 'NovaC' : 'Array',
            'iterations' => $iterations,
            'method'     => $method,
            'time'       => (string)$time,
        ]
    );
}
echo PHP_EOL;
