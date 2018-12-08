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

use Novactive\Collection\Collection;
use Novactive\Collection\Factory;

class ToArrayCollectionTest extends UnitTestCase
{
    public function testCollectionToArrayConvertsItemsToArray(): void
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertInstanceOf(Collection::class, $coll);
        $this->assertEquals(
            ['Chelsea', 'Adella', 'Monte', 'Maye', 'Lottie', 'Don', 'Dayton', 'Kirk', 'Troy', 'Nakia'],
            $coll->toArray()
        );
    }
}
