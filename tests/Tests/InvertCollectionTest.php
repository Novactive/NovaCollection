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

class InvertCollectionTest extends UnitTestCase
{
    public function testInvertCollection(): void
    {
        $coll    = Factory::create($this->fixtures['names']);
        $keys    = $coll->keys();
        $values  = $coll->values();
        $flipped = $coll->invert();
        $this->assertCount($coll->count(), $flipped);
        $this->assertEquals($keys, $flipped->values());
        $this->assertEquals($values, $flipped->keys());
        $unflipped = $flipped->invert();
        $this->assertEquals($coll, $unflipped, '2 invert MUST return an equal collection');
        $this->assertSame($coll, $unflipped, '2 invert MUST return the same collection');
    }

    public function conflictInversionProvider(): array
    {
        return [
            [
                [
                    'one'   => 'orange',
                    'two'   => 'banana',
                    'three' => 'plop',
                    'plop'  => 'coffee',
                    'five'  => 'caramel',
                    'plopx' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider conflictInversionProvider
     */
    public function testInvertWithConflictsCollection($items): void
    {
        $coll = Factory::create($items);
        $flip = $coll->flip();
        $coll->invert();
        $this->assertEquals($coll, $flip);
        $this->assertNotSame($coll, $flip);
    }
}
