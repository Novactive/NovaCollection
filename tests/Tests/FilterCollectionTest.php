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
 * Class FilterCollectionTest.
 */
class FilterCollectionTest extends UnitTestCase
{

    public function testFilterReturnsNewCollectionFilteredByPredicateCallback()
    {
        $predicate = function ($val, $key) {
            return strlen($val) > 4;
        };

        $coll = Factory::create($this->fixtures['names']);

        $this->assertCount(10, $coll);
        $filtered = $coll->filter($predicate);
        $this->assertInstanceOf(Collection::class, $filtered);
        $this->assertNotSame($coll, $filtered);
        $this->assertCount(10, $coll);
        $this->assertCount(6, $filtered);
        $this->assertEquals(
            [
                0 => 'Chelsea',
                1 => 'Adella',
                2 => 'Monte',
                4 => 'Lottie',
                6 => 'Dayton',
                9 => 'Nakia',
            ],
            $filtered->toArray()
        );
    }

}
