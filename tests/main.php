<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
include __DIR__.'/bootstrap.php';

use Novactive\Collection\Collection;
use Novactive\Collection\Factory;

$dump = function (Collection $collection, $name) {
    echo "---{$name}---".PHP_EOL;
    $collection->each(
        function ($value, $key, $i) {
            echo "{$i}: {$key} => {$value}".PHP_EOL;
        }
    );
};

$c1 = Factory::create(
    [
        'key1'     => 'value1',
        'key2'     => 'value2',
        'key3'     => 'value3',
        'orange',
        'dark',
        'aNullKey' => null,
    ],
    'Novactive\Collection\Debug\Collection'
);

$c1Clone = clone $c1;

$dump($c1, 'c1');

$c2 = $c1->map(
    function ($value) {
        return $value.' mapped!';
    }
);

$dump($c1, 'c1');
$dump($c2, 'c2');

$c2->transform(
    function ($value) {
        return $value.' transformed!';
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
        return $key === 'key2';
    }
);

$dump($c2, 'c2');

echo 'REDUCE=';
echo $c1->reduce(
    function ($accumulator, $value, $key) {
        return $accumulator.":({$key},{$value})";
    }
);
echo PHP_EOL;

echo 'ASSERT YES:';
echo $c1->assert(
    function ($value, $key) {
        return !empty($value);
    },
    true
);
echo PHP_EOL;

echo 'ASSERT NO:';
echo $c1->assert(
    function ($value, $key) {
        return !empty($value);
    },
    false
);
echo PHP_EOL;

$c1Clone->prune(
    function ($value, $key) {
        return $key === 'aNullKey';
    }
);

$dump($c1Clone, 'c2');

$plus = new Collection(
    [
        'key3' => 'value3overrided',
        'key4' => 'value4',
        'key5' => 'value5',
    ]
);

$dump($c1->union($plus), 'UNION');
$dump($c1->merge($plus), 'UNION');

$dump($c1, 'c1');

$c1->iunion($plus);
$dump($c1, 'c1');

$c1->imerge($plus);
$dump($c1, 'c1');

echo '------'.PHP_EOL;
