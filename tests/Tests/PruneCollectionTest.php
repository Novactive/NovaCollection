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
 * Class PruneCollectionTest.
 */
class PruneCollectionTest extends UnitTestCase
{

    public function testPruneFiltersCollectionInPlace()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertCount(10, $coll);
        $this->assertSame(
            $coll,
            $coll->prune(
                function ($value, $key) {
                    return $key % 2 == 0;
                }
            )
        );
        $this->assertCount(5, $coll);
        $this->assertSame(
            $coll,
            $coll->prune(
                function ($value, $key) {
                    return strlen($value) >= 6;
                }
            )
        );
        $this->assertCount(3, $coll);
    }

}
