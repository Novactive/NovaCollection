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

class KeyOfCollectionTest extends UnitTestCase
{
    public function valueProvider(): array
    {
        return [
            ['Don', 5],
            ['Monte', 2],
            ['Plop', null],
        ];
    }

    public function value2Provider(): array
    {
        return [
            ['third', '3rd'],
            ['first', '1st'],
            ['plopx', null],
        ];
    }

    /**
     * @dataProvider valueProvider
     */
    public function testKeyOfInCollection($value, $expected): void
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals($expected, $coll->keyOf($value));
    }

    /**
     * @dataProvider value2Provider
     */
    public function testKeyOf2InCollection($value, $expected): void
    {
        $coll = Factory::create($this->fixtures['assoc']);
        $this->assertEquals($expected, $coll->keyOf($value));
    }
}
