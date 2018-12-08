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

class AddCollectionTest extends UnitTestCase
{
    /**
     * @expectedException \TypeError
     */
    public function testAppendException(): void
    {
        $array = Factory::create($this->fixtures['array']);
        $array->append('plop');
    }

    public function testAddAppendsItemToCollectionWithNextNumericIndex(): void
    {
        $array = Factory::create($this->fixtures['array']);
        $this->assertEquals(
            [
                0 => 'first',
                1 => 'second',
                2 => 'third',
            ],
            $array->toArray()
        );
        $array->add('fourth');
        $this->assertSame(
            [
                0 => 'first',
                1 => 'second',
                2 => 'third',
                3 => 'fourth',
            ],
            $array->toArray()
        );
        $array->add('fifth');
        $this->assertSame(
            [
                0 => 'first',
                1 => 'second',
                2 => 'third',
                3 => 'fourth',
                4 => 'fifth',
            ],
            $array->toArray()
        );

        $assoc = Factory::create($this->fixtures['assoc']);
        $this->assertEquals(
            [
                '1st' => 'first',
                '2nd' => 'second',
                '3rd' => 'third',
            ],
            $assoc->toArray()
        );
        $assoc->add('fourth');
        $this->assertSame(
            [
                '1st' => 'first',
                '2nd' => 'second',
                '3rd' => 'third',
                0     => 'fourth',
            ],
            $assoc->toArray()
        );
        $assoc->add('fifth');
        $this->assertSame(
            [
                '1st' => 'first',
                '2nd' => 'second',
                '3rd' => 'third',
                0     => 'fourth',
                1     => 'fifth',
            ],
            $assoc->toArray()
        );
    }
}
