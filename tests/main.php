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

include __DIR__."/../vendor/autoload.php";

use Novactive\Collection\Collection as NovaCollection;

$dump = function (NovaCollection $collection, $name) {
    print "---{$name}---".PHP_EOL;
    $collection->each(
        function ($value, $key, $i) {
            print "{$i}: {$key} => {$value}".PHP_EOL;
        }
    );
};

$c1 = new NovaCollection(
    [
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
        'orange',
        'dark'
    ]
);
$dump($c1, 'c1');

$c2 = $c1->map(
    function ($value) {
        return $value." mapped!";
    }
);

$dump($c1, 'c1');
$dump($c2, 'c2');

$c2->transform(
    function ($value) {
        return $value." transformed!";
    }
);

$dump($c1, 'c1');
$dump($c2, 'c2');

$c3 = $c1->filter(
    function ($value, $key) {
        return strlen($key) >= 3;
    }
);
$dump($c3, 'c3');

$c2->prune(
    function ($value, $key) {
        return $key === "key2";
    }
);

$dump($c2, 'c2');

print "REDUCE".$c1->reduce(
        function ($accumulator, $value, $key) {
            return $accumulator.":({$key},{$value})";
        }
    ).PHP_EOL;

print "CHECK YES:".$c1->check(
        function ($value, $key) {
            return !empty($value);
        },
        true
    ).PHP_EOL;

print "CHECK NO:".$c1->check(
        function ($value, $key) {
            return !empty($value);
        },
        false
    ).PHP_EOL;

print "------".PHP_EOL;
