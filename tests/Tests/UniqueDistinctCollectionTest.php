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

class UniqueDistinctCollectionTest extends UnitTestCase
{
    public function testUniqueCollection(): void
    {
        $array  = Factory::create($this->fixtures['array']);
        $array2 = Factory::create($this->fixtures['array']);
        $array->append($array2);
        $this->assertCount($array2->count() * 2, $array);
        $unify = $array->unique();
        $this->assertEquals($array2, $unify);
        $this->assertNotSame($array, $unify);
        $this->assertNotSame($array2, $unify);
    }

    public function testDistinctCollection(): void
    {
        $array  = Factory::create($this->fixtures['array']);
        $array2 = Factory::create($this->fixtures['array']);
        $array->append($array2);
        $this->assertCount($array2->count() * 2, $array);
        $unify = $array->distinct();
        $this->assertEquals($array2, $unify);
        $this->assertSame($array, $unify);
        $this->assertNotSame($array2, $unify);
    }
}
