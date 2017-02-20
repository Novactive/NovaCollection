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
 * Class UnionCollectionTest.
 */
class UnionCollectionTest extends UnitTestCase
{
    public function testUnioneollection()
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

    public function testUnionInPlaceCollection()
    {
        $coll  = Factory::create($this->fixtures['names']);
        $coll2 = Factory::create($this->fixtures['assoc']);
        $tab   = ['plop', 'plop'];

        $newColl = $coll->absorb($coll2)->absorb($tab);
        $this->assertEquals($newColl->atIndex(0), 'Chelsea');
        $this->assertEquals($newColl->keyOf('third'), '3rd');

        $this->assertSame($newColl, $coll);
    }

    public function exceptionProvider()
    {
        return [
            ['plop', 'union'],
            [new \stdClass(), 'union'],
            [123112.31, 'union'],
            ['plop', 'absorb'],
            [new \stdClass(), 'absorb'],
            [123112.31, 'absorb'],
        ];
    }

    /**
     * @dataProvider exceptionProvider
     * @expectedException \InvalidArgumentException
     */
    public function testMergeExceptionCollection($items, $method)
    {
        $coll = Factory::create([123, 123, 123, 123, 123, 12312, 33]);
        $coll->$method($items);
    }
}
