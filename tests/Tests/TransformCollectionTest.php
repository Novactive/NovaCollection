<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Novactive\Tests;

use Novactive\Collection\Collection;
use Novactive\Collection\Factory;

/**
 * Class TransformCollectionTest.
 */
class TransformCollectionTest extends UnitTestCase
{
    public function testTransformTransformsCollectionInPlace()
    {
        $coll        = Factory::create($this->fixtures['names']);
        $transformed = $coll->transform(
            function ($val, $key) {
                return $val.$key;
            }
        );
        $this->assertInstanceOf(Collection::class, $transformed);
        $this->assertSame($coll, $transformed);
        $this->assertEquals(
            [
                'Chelsea0',
                'Adella1',
                'Monte2',
                'Maye3',
                'Lottie4',
                'Don5',
                'Dayton6',
                'Kirk7',
                'Troy8',
                'Nakia9',
            ],
            $coll->toArray()
        );
    }
}
