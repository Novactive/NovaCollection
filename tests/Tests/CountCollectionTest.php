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

class CountCollectionTest extends UnitTestCase
{
    public function testCountReturnsTotalCollectionCount(): void
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertCount(10, $coll);
        $this->assertEquals(10, $coll->count());
        $this->assertEquals(10, count($coll));
    }
}
