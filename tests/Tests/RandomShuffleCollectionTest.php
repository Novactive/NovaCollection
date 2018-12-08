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

class RandomShuffleCollectionTest extends UnitTestCase
{
    public function testShuffleCollection(): void
    {
        $names       = Factory::create($this->fixtures['names']);
        $namesClone  = clone $names;
        $aCollection = $names->shuffle();

        $this->assertSame($names, $aCollection);
        $this->assertNotEquals($namesClone, $aCollection);

        $this->assertCount($names->count(), $aCollection);
    }

    public function testRandomCollection(): void
    {
        $names       = Factory::create($this->fixtures['names']);
        $namesClone  = clone $names;
        $aCollection = $names->random();

        $this->assertNotSame($names, $aCollection);
        $this->assertNotEquals($namesClone, $aCollection);

        $this->assertCount($names->count(), $aCollection);
    }
}
