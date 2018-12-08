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

class ReverseInverseCollectionTest extends UnitTestCase
{
    public function testReverseCollection(): void
    {
        $array    = Factory::create($this->fixtures['array']);
        $reverse  = Factory::create(['third', 'second', 'first']);
        $reversed = $array->reverse()->values();
        $this->assertEquals($reverse, $reversed);
        $this->assertNotSame($array, $reversed);
    }

    public function testInverseCollection(): void
    {
        $array    = Factory::create($this->fixtures['array']);
        $reverse  = Factory::create(['third', 'second', 'first']);
        $reversed = $array->inverse();
        $this->assertEquals($reverse, $reversed->values());
        $this->assertSame($array, $reversed);
    }
}
