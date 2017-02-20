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

use Novactive\Collection\Collection;
use Novactive\Collection\Factory;

/**
 * Class CollectionTest.
 */
class CollectionTest extends UnitTestCase
{
    public function testInstantiateCollectionWithNoParams()
    {
        $coll = Factory::create();
        $this->assertInstanceOf(Collection::class, $coll);
    }

    public function testJsonSerialize()
    {
        $coll = Factory::create($this->fixtures['assoc']);
        $json = json_encode($coll);
        $this->assertEquals('{"1st":"first","2nd":"second","3rd":"third"}', $json);

        $jsonDecoded = json_decode($json, true);
        $coll2       = Factory::create($jsonDecoded);
        $this->assertEquals($coll2, $coll);
    }

    public function testJsonSerialize2()
    {
        $coll  = Factory::create($this->fixtures['assoc']);
        $coll2 = Factory::create('{"1st":"first","2nd":"second","3rd":"third"}');
        $this->assertEquals($coll2, $coll);
    }

    public function testDump()
    {
        $coll  = Factory::create($this->fixtures['assoc']);
        $coll2 = $coll->dump();
        $this->assertEquals($coll2, $coll);
        $this->assertSame($coll2, $coll);
    }

    public function factoryProvider()
    {
        $assoc = ['1st' => 'first', '2nd' => 'second', '3rd' => 'third'];

        return [
            [$assoc],
            ['{"1st":"first","2nd":"second","3rd":"third"}'],
            [Factory::create($assoc)],
        ];
    }

    /**
     * @dataProvider factoryProvider
     */
    public function testFactory($arg)
    {
        $coll = Factory::create($arg);
        $this->assertInstanceOf(Collection::class, $coll);

        $coll2 = Collection($arg);
        $this->assertInstanceOf(Collection::class, $coll2);
        $this->assertEquals($coll, $coll2);

        $coll3 = NovaCollection($arg);
        $this->assertInstanceOf(Collection::class, $coll3);
        $this->assertEquals($coll3, $coll2);
    }
}
