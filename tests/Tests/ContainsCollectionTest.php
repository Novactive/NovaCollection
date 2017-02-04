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
 * Class ContainsCollectionTest.
 */
class ContainsCollectionTest extends UnitTestCase
{

    public function testContainsReturnsTrueIfValueFoundInCollection()
    {
        $coll = Factory::create($this->fixtures['assoc']);
        $this->assertTrue($coll->contains('first'));
        $this->assertTrue($coll->contains('second'));
        $this->assertTrue($coll->contains('third'));
        $this->assertFalse($coll->contains('fourth'));
        $this->assertFalse($coll->contains('foo'));
        $coll->add('foo');
        $this->assertTrue($coll->contains('foo'));

        $this->assertFalse($coll->contains(100));
        $coll->add(100);
        $this->assertFalse(
            $coll->contains('100'),
            'Collection::contains method uses strict type comparison so that 100 !== "100"'
        );
        // @todo
        //      I think we should add a second argument to contains() that does an additional check against
        //      the index so taht you can check whether the collection contains a certain value at a certain index.
        //      I think we should also allow for $value argument to be a callable. When it is a callable,
        //      it should be used instead of in_array to determine whether the collection contains whatever...
        $this->assertTrue($coll->contains(100));
    }

}
