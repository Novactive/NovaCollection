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
 * Class AssertCollectionTest.
 */
class AssertCollectionTest extends UnitTestCase
{
    public function testAssertALlStringInCollection()
    {
        $coll  = Factory::create($this->fixtures['names']);
        $coll2 = Factory::create($this->fixtures['digits']);

        $allString = function ($value, $key, $index) {
            return is_string($value);
        };

        $allArray = function ($value, $key, $index) {
            return is_array($value);
        };

        $allInt = function ($value, $key, $index) {
            return is_int($value);
        };

        $this->assertTrue($coll->assert($allString, true));
        $this->assertFalse($coll->assert($allArray, true));
        $this->assertTrue($coll->assert($allArray, false));
        $this->assertTrue($coll2->assert($allInt, true));

        $coll2->add('plop')->append([12312, 123, 123, 12312, 312, 3123, 123, 123, 123]);

        $this->assertFalse($coll2->assert($allInt, true));
    }

    public function testExists()
    {
        $coll = Factory::create($this->fixtures['names']);

        $test = function ($value, $key, $index) {
            return $value == 'Monte';
        };

        $test2 = function ($value, $key, $index) {
            return $value == 'Monte2';
        };
        $this->assertTrue($coll->exists($test));
        $this->assertFalse($coll->exists($test2));
    }
}
