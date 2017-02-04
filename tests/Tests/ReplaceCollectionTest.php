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
 * Class ReplaceCollectionTest.
 */
class ReplaceCollectionTest extends UnitTestCase
{
    public function testReplaceCombinesInPlace()
    {
        $coll   = Factory::create($this->fixtures['names']);
        $return = $coll->replace($this->fixtures['emails']);
        $this->assertSame($coll, $return);
        $this->assertEquals(
            [
                'Chelsea' => 'colleen.mills@example.org',
                'Adella'  => 'hilda01@example.net',
                'Monte'   => 'leffler.ron@example.org',
                'Maye'    => 'jboyle@example.net',
                'Lottie'  => 'esta10@example.com',
                'Don'     => 'fcormier@example.com',
                'Dayton'  => 'verda25@example.com',
                'Kirk'    => 'mparker@example.org',
                'Troy'    => 'apollich@example.net',
                'Nakia'   => 'hrussel@example.net',
            ],
            $coll->toArray()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid input type for replace.
     */
    public function testReplaceThrowsExceptionIfInvalidInput()
    {
        $coll = Factory::create($this->fixtures['names']);
        $coll->replace('not an array');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid input for replace, number of items does not match.
     */
    public function testReplaceThrowsExceptionIfIncomingTraversableCountIsNotSameAsCollection()
    {
        $coll = Factory::create($this->fixtures['names']);
        $coll->replace([1, 2, 3]);
    }
}
