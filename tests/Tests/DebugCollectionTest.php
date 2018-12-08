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

use Novactive\Collection\Collection;
use Novactive\Collection\Factory;

class DebugCollectionTest extends CollectionTest
{
    public function testInstantiateCollectionWithNoParams(): void
    {
        $coll = Factory::create([], 'Novactive\Collection\Debug\Collection');
        $this->assertInstanceOf(Collection::class, $coll);
    }

    public function testDump(): void
    {
        $coll  = Factory::create([], 'Novactive\Collection\Debug\Collection');
        $coll2 = $coll->dump();
        $this->assertEquals($coll2, $coll);
        $this->assertSame($coll2, $coll);
    }
}
