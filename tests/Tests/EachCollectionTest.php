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
 * Class EachCollectionTest.
 */
class EachCollectionTest extends UnitTestCase
{

    public function testEachLoopsOverEveryItemInCollectionCallingCallback()
    {
        $coll       = Factory::create($this->fixtures['names']);
        $namesCount = count($this->fixtures['names']);
        $recorder   = $this->getMethodCallRecorderForCount($namesCount);

        // this will test that touch() is called $namesCount times
        $return = $coll->each(
            function ($val, $key, $iter = null) use ($recorder, $coll) {
                $this->assertTrue(is_string($val));
                $this->assertTrue(is_numeric($key));

                if (!$coll instanceof \Novactive\Tests\Perfs\ArrayMethodCollection) {
                    $this->assertTrue(is_numeric($iter));
                }

                $recorder->touch();
            }
        );
        $this->assertSame($coll, $return, 'Collection::each() should return the collection itself.');
    }

}
