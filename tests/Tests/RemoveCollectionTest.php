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
 * Class RemoveCollectionTest.
 */
class RemoveCollectionTest extends UnitTestCase
{

    public function testRemoveRemovesItemByKey()
    {
        $exp  = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertTrue($coll->containsKey('2nd'));
        $removed = $coll->remove('2nd');
        $this->assertFalse($coll->containsKey('2nd'));
        $this->assertSame($coll, $removed, 'Remove method should return the collection itself.');
        $return = $coll->remove('2nd');
        $this->assertSame(
            $coll,
            $return,
            'Attempting to remove an item that does not exist should still return the collection itself.'
        );
    }

}
