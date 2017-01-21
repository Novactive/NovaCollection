<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 *
 * @copyright 2017 Novactive
 * @license   MIT
 */
namespace NovactiveTest;

use Novactive\Collection\Collection;

class CollectionTest extends UnitTestCase
{
    public function testInstantiateCollectionWithNoParams()
    {
        $coll = new Collection;
        $this->assertInstanceOf(Collection::class, $coll);
    }

    public function testCollectionToArrayConvertsItemsToArray()
    {
        $coll = new Collection($this->fixtures['names']);
        $this->assertInstanceOf(Collection::class, $coll);
        $this->assertEquals(['Chelsea','Adella','Monte','Maye','Lottie','Don','Dayton','Kirk','Troy','Nakia'], $coll->toArray());
    }
}
