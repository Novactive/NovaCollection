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
 * Class CombineCollectionTest.
 */
class CombineCollectionTest extends UnitTestCase
{
    public function testCombineReturnsCollectionWithExistingKeysAndIncomingValues()
    {
        $coll = Factory::create($this->fixtures['names']);

        $this->assertSame(
            [
                'Chelsea',
                'Adella',
                'Monte',
                'Maye',
                'Lottie',
                'Don',
                'Dayton',
                'Kirk',
                'Troy',
                'Nakia',
            ],
            $orig = $coll->toArray()
        );

        $newcoll = $coll->combine($this->fixtures['emails']);
        $this->assertNotSame($coll, $newcoll);
        $this->assertSame(
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
            $newcoll->toArray()
        );
        $this->assertSame(
            $orig,
            $coll->toArray(),
            'Ensure that Collection::combine() has not changed the original collection.'
        );
    }

    public function testCombineAcceptsTraversable()
    {
        $stub = $this->getIteratorForArray($this->fixtures['emails']);
        $coll = Factory::create($this->fixtures['names']);
        $this->assertSame(
            [
                'Chelsea',
                'Adella',
                'Monte',
                'Maye',
                'Lottie',
                'Don',
                'Dayton',
                'Kirk',
                'Troy',
                'Nakia',
            ],
            $coll->toArray()
        );
        $this->assertSame(
            [
                'colleen.mills@example.org',
                'hilda01@example.net',
                'leffler.ron@example.org',
                'jboyle@example.net',
                'esta10@example.com',
                'fcormier@example.com',
                'verda25@example.com',
                'mparker@example.org',
                'apollich@example.net',
                'hrussel@example.net',
            ],
            Factory::getArrayForItems($stub)
        );
        $orig   = $coll->toArray();
        $return = $coll->combine($stub);
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
            $return->toArray()
        );
        $this->assertSame(
            $orig,
            $coll->toArray(),
            'Ensure that Collection::combine() has not changed the original collection.'
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid input type for combine.
     */
    public function testCombineThrowsExceptionIfInvalidInput()
    {
        $coll = Factory::create($this->fixtures['names']);
        $coll->combine('not an array');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid input for combine, number of items does not match.
     */
    public function testCombineThrowsExceptionIfIncomingTraversableCountIsNotSameAsCollection()
    {
        $coll = Factory::create($this->fixtures['names']);
        $coll->combine([1, 2, 3]);
    }
}
