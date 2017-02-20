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
 * Class SliceCollectionTest.
 */
class SliceCollectionTest extends UnitTestCase
{
    public function testSliceCollection()
    {
        $names    = Factory::create($this->fixtures['names']);
        $sliced   = $names->slice(1, 3);
        $expected = Factory::create(['Adella', 'Monte', 'Maye']);
        $this->assertEquals($expected, $sliced->values());
        $this->assertNotSame($names, $sliced);
    }

    public function testKeepCollection()
    {
        $names    = Factory::create($this->fixtures['names']);
        $sliced   = $names->keep(1, 3);
        $expected = Factory::create(['Adella', 'Monte', 'Maye']);
        $this->assertEquals($expected, $sliced->values());
        $this->assertSame($names, $sliced);
    }

    public function testCutCollection()
    {
        $names    = Factory::create($this->fixtures['names'])->keep(0, 4);
        $sliced   = $names->cut(1, 2);
        $expected = Factory::create(['Chelsea', 'Maye']);
        $this->assertEquals($expected, $sliced->values());
        $this->assertSame($names, $sliced);
    }

    public function testCutMaxCollection()
    {
        $names    = Factory::create($this->fixtures['names'])->keep(0, 4);
        $sliced   = $names->cut(1);
        $expected = Factory::create(['Chelsea']);
        $this->assertEquals($expected, $sliced->values());
        $this->assertSame($names, $sliced);
    }

    public function testCutNegativeCollection()
    {
        $names    = Factory::create($this->fixtures['names'])->keep(0, 4);
        $sliced   = $names->cut(-2, 1);
        $expected = Factory::create(['Chelsea', 'Adella', 'Maye']);
        $this->assertEquals($expected, $sliced->values());
        $this->assertSame($names, $sliced);
    }
}
