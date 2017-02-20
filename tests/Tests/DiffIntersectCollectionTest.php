<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Novactive\Tests;

use Novactive\Collection\Factory;

/**
 * Class DiffIntersectCollectionTest.
 */
class DiffIntersectCollectionTest extends UnitTestCase
{
    public function testDiffCollection()
    {
        $array1 = ['a' => 'green', 'red', 'blue', 'red'];
        $array2 = ['b' => 'green', 'yellow', 'red'];
        $array1 = Factory::create($array1);
        $array2 = Factory::create($array2);
        $result = array_diff($array1->toArray(), $array2->toArray());
        $this->assertEquals($result, $array1->diff($array2)->toArray());
    }

    public function testDiffKeysCollection()
    {
        $array1   =  ['blue' => 1, 'red' => 2, 'green' => 3, 'purple' => 4];
        $array2   =  ['green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan' => 8];
        $array1   = Factory::create($array1);
        $array2   = Factory::create($array2);
        $diffed   = $array1->diffKeys($array2);
        $expected = array_diff_key($array1->toArray(), $array2->toArray());
        $this->assertEquals($expected, $diffed->toArray());
    }

    public function testIntersectCollection()
    {
        $array1 = ['a' => 'green', 'red', 'blue'];
        $array2 = ['b' => 'green', 'yellow', 'red'];
        $array1 = Factory::create($array1);
        $array2 = Factory::create($array2);
        $result = array_intersect($array1->toArray(), $array2->toArray());
        $this->assertEquals($result, $array1->intersect($array2)->toArray());
    }

    public function testIntersectKeysCollection()
    {
        $array1   =  ['blue' => 1, 'red' => 2, 'green' => 3, 'purple' => 4];
        $array2   =  ['green' => 5, 'blue' => 6, 'yellow' => 7, 'cyan' => 8];
        $array1   = Factory::create($array1);
        $array2   = Factory::create($array2);
        $diffed   = $array1->intersectKeys($array2);
        $expected = $result = array_intersect_key($array1->toArray(), $array2->toArray());
        $this->assertEquals($expected, $diffed->toArray());
    }
}
