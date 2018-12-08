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

class LastCollectionTest extends UnitTestCase
{
    public function letterProvider(): array
    {
        return [
            ['D', 'Dayton'],
            ['M', 'Maye'],
        ];
    }

    public function testLastReturnsLastItemInCollection(): void
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals('Nakia', $coll->last());
    }

    /**
     * @dataProvider letterProvider
     */
    public function testLastReturnsFirstItemInCollectionWithCallback($letter, $expected): void
    {
        $coll  = Factory::create($this->fixtures['names']);
        $first = $coll->last(
            function ($value) use ($letter) {
                return substr($value, 0, 1) == $letter;
            }
        );
        $this->assertEquals($expected, $first);
    }
}
