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

class ShiftCollectionTest extends UnitTestCase
{
    public function testShiftInCollection(): void
    {
        $coll        = Factory::create($this->fixtures['names']);
        $currentSize = $coll->count();
        $pulled      = $coll->shift();
        $this->assertEquals('Chelsea', $pulled);
        $this->assertEquals($currentSize - 1, $coll->count());

        $pulled = $coll->shift();

        $this->assertEquals('Adella', $pulled);
        $this->assertEquals($currentSize - 2, $coll->count());
    }
}
