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
 * Class PullCollectionTest.
 */
class PullCollectionTest extends UnitTestCase
{

    public function testPullRemovesItemFromCollectionAndReturnsIt()
    {
        $coll = Factory::create($this->fixtures['assoc']);
        $this->assertCount(3, $coll);
        $this->assertTrue($coll->containsKey('2nd'));
        $this->assertSame(
            'second',
            $pulled = $coll->pull('2nd'),
            'Collection::pull() should return an item by key and return it.'
        );
        $this->assertFalse($coll->containsKey('2nd'));
        $this->assertCount(2, $coll);
        $this->assertNull($coll->pull('2nd'), 'Collection::pull() should return null if key does not exist.');
    }

}
