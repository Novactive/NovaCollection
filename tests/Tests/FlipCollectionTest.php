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

class FlipCollectionTest extends UnitTestCase
{
    public function testFlipCollection(): void
    {
        $coll    = Factory::create($this->fixtures['names']);
        $keys    = $coll->keys();
        $values  = $coll->values();
        $flipped = $coll->flip();
        $this->assertCount($coll->count(), $flipped);
        $this->assertEquals($keys, $flipped->values());
        $this->assertEquals($values, $flipped->keys());
        $unflipped = $flipped->flip();
        $this->assertEquals($coll, $unflipped, '2 flip MUST return an equal collection');
        $this->assertNotSame($coll, $unflipped, '2 flip MUST NOT return the same collection');
    }
}
