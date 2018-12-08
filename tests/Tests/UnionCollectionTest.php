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

class UnionCollectionTest extends UnitTestCase
{
    public function testUnioneollection(): void
    {
        $coll  = Factory::create($this->fixtures['names']);
        $coll2 = Factory::create($this->fixtures['assoc']);
        $tab   = ['plop', 'plop', 'yeah' => 'alright'];

        $newColl = $coll->union($coll2)->union($tab);

        $this->assertEquals($coll->atIndex(0), 'Chelsea');
        $this->assertEquals($newColl->atIndex(0), 'Chelsea');
        $this->assertEquals($newColl->keyOf('third'), '3rd');
        $this->assertEquals($newColl->keyOf('alright'), 'yeah');
        $this->assertNotSame($newColl, $coll);
    }

    public function testUnionInPlaceCollection(): void
    {
        $coll  = Factory::create($this->fixtures['names']);
        $coll2 = Factory::create($this->fixtures['assoc']);
        $tab   = ['plop', 'plop'];

        $newColl = $coll->absorb($coll2)->absorb($tab);
        $this->assertEquals($newColl->atIndex(0), 'Chelsea');
        $this->assertEquals($newColl->keyOf('third'), '3rd');

        $this->assertSame($newColl, $coll);
    }
}
