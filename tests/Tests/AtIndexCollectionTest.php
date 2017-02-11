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

use Novactive\Collection\Factory;

/**
 * Class AtIndexCollectionTest.
 */
class AtIndexCollectionTest extends UnitTestCase
{
    public function indexProvider()
    {
        return [
            [1, 'Adella'],
            [3, 'Maye'],
            [4, 'Lottie'],
            [7, 'Kirk'],
            [666, null],
        ];
    }

    /**
     * @dataProvider indexProvider
     */
    public function testAtIndexInCollection($index, $expected)
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals($expected, $coll->atIndex($index));
    }
}
