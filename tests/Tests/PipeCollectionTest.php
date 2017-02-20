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
 * Class PipeCollectionTest.
 */
class PipeCollectionTest extends UnitTestCase
{
    public function testPipeSimpleCollection()
    {
        $names = Factory::create($this->fixtures['names']);

        $count = $names->pipe(
            function (Collection $collection) {
                return $collection->count();
            }
        );

        $this->assertEquals($count, $names->count());
    }

    public function testMemoryPipeCollection()
    {
        $names       = Factory::create($this->fixtures['names']);
        $aCollection = $names->pipe(
            function (Collection $collection) {
                return $collection->transform(
                    function ($value) {
                        return $value.'plop';
                    }
                );
            }
        );

        $this->assertEquals($names, $aCollection);
        $this->assertSame($names, $aCollection);
    }
}
