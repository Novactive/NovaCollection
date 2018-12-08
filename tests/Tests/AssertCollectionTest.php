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

namespace Novactive\Tests;

use Novactive\Collection\Factory;

class AssertCollectionTest extends UnitTestCase
{
    public function testAssertALlStringInCollection(): void
    {
        $coll  = Factory::create($this->fixtures['names']);
        $coll2 = Factory::create($this->fixtures['digits']);

        $allString = function ($value, $key, $index) {
            return \is_string($value);
        };

        $allArray = function ($value, $key, $index) {
            return \is_array($value);
        };

        $allInt = function ($value, $key, $index) {
            return \is_int($value);
        };

        $this->assertTrue($coll->assert($allString, true));
        $this->assertFalse($coll->assert($allArray, true));
        $this->assertTrue($coll->assert($allArray, false));
        $this->assertTrue($coll2->assert($allInt, true));

        $coll2->add('plop')->append([12312, 123, 123, 12312, 312, 3123, 123, 123, 123]);

        $this->assertFalse($coll2->assert($allInt, true));
    }

    public function testExists(): void
    {
        $coll = Factory::create($this->fixtures['names']);

        $test = function ($value, $key, $index) {
            return 'Monte' === $value;
        };

        $test2 = function ($value, $key, $index) {
            return 'Monte2' === $value;
        };
        $this->assertTrue($coll->exists($test));
        $this->assertFalse($coll->exists($test2));
    }
}
