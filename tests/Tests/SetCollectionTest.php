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

class SetCollectionTest extends UnitTestCase
{
    public function testSetWillSetValueOnCollectionInPlace(): void
    {
        $exp  = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertEquals($exp, $coll->toArray());
        $this->assertSame($coll, $coll->set('1st', 'worst'), 'Collection::set() should return the collection itself.');
        $exp['1st'] = 'worst';
        $this->assertEquals($exp, $coll->toArray());
        $coll->set('first', 'worst');
        $exp['first'] = 'worst';
        $this->assertEquals($exp, $coll->toArray());
    }
}
