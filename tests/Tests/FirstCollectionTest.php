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

class FirstCollectionTest extends UnitTestCase
{
    public function letterProvider(): array
    {
        return [
            ['D', 'Don'],
            ['M', 'Monte'],
        ];
    }

    public function testFirstReturnsFirstItemInCollection(): void
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals('Chelsea', $coll->first());
    }

    /**
     * @dataProvider letterProvider
     */
    public function testFirstReturnsFirstItemInCollectionWithCallback($letter, $expected): void
    {
        $coll  = Factory::create($this->fixtures['names']);
        $first = $coll->first(
            function ($value) use ($letter) {
                return substr($value, 0, 1) === $letter;
            }
        );
        $this->assertEquals($expected, $first);
    }
}
