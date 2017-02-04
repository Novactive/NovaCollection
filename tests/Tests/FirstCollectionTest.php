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
 * Class FirstCollectionTest.
 */
class FirstCollectionTest extends UnitTestCase
{

    public function testFirstReturnsFirstItemInCollection()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals('Chelsea', $coll->first());
    }

}
