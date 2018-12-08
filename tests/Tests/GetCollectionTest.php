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

class GetCollectionTest extends UnitTestCase
{
    public function testGetReturnsItemIfItExists(): void
    {
        $exp  = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertEquals('second', $coll->get('2nd'));
        $coll->remove('2nd');
        $this->assertNull($coll->get('2nd'));
        $this->assertEquals(
            'default',
            $coll->get('2nd', 'default'),
            'If a default is provided, Collection::get() should return it if no item is found.'
        );
    }
}
