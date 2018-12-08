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

class MapKeysCollectionTest extends UnitTestCase
{
    public function testMapReturnsNewCollectionWithTransformedKeys(): void
    {
        $coll        = Factory::create($this->fixtures['names']);
        $transformed = $coll->mapKeys(
            function ($val, $key, $index) {
                return "{$key}-{$val}-{$index}";
            }
        );
        $this->assertInstanceOf(Collection::class, $transformed);
        $this->assertNotSame($coll, $transformed);
        $this->assertEquals(
            [
                '0-Chelsea-0' => 'Chelsea',
                '1-Adella-1'  => 'Adella',
                '2-Monte-2'   => 'Monte',
                '3-Maye-3'    => 'Maye',
                '4-Lottie-4'  => 'Lottie',
                '5-Don-5'     => 'Don',
                '6-Dayton-6'  => 'Dayton',
                '7-Kirk-7'    => 'Kirk',
                '8-Troy-8'    => 'Troy',
                '9-Nakia-9'   => 'Nakia',
            ],
            $transformed->toArray()
        );
    }

    public function testMapReturnsNewCollectionWithTransformedKeyValues(): void
    {
        $coll        = Factory::create($this->fixtures['names']);
        $transformed = $coll->mapKeys(
            function ($val, $key, $index) {
                return "{$key}-{$val}-{$index}";
            },
            function ($val) {
                return "Plop-{$val}";
            }
        );
        $this->assertInstanceOf(Collection::class, $transformed);
        $this->assertNotSame($coll, $transformed);
        $this->assertEquals(
            [
                '0-Chelsea-0' => 'Plop-Chelsea',
                '1-Adella-1'  => 'Plop-Adella',
                '2-Monte-2'   => 'Plop-Monte',
                '3-Maye-3'    => 'Plop-Maye',
                '4-Lottie-4'  => 'Plop-Lottie',
                '5-Don-5'     => 'Plop-Don',
                '6-Dayton-6'  => 'Plop-Dayton',
                '7-Kirk-7'    => 'Plop-Kirk',
                '8-Troy-8'    => 'Plop-Troy',
                '9-Nakia-9'   => 'Plop-Nakia',
            ],
            $transformed->toArray()
        );
    }
}
