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

class SplitChunkCollectionTest extends UnitTestCase
{
    public function testSplitCollection(): void
    {
        $names = Factory::create($this->fixtures['names']);
        $split = $names->split(3);
        $this->assertCount(3, $split);
        $this->assertNotSame($names, $split);
    }

    public function testChunkCollection(): void
    {
        $names = Factory::create($this->fixtures['names']);
        $split = $names->chunk(2);
        $this->assertCount(intval(floor($names->count() / 2)), $split);
        $this->assertNotSame($names, $split);
    }
}
