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

class CombineKeysCollectionTest extends UnitTestCase
{
    public function testCombineKeysUsesIncomingTraversableAsKeysForCollectionsValues(): void
    {
        $coll    = Factory::create($this->fixtures['names']);
        $orig    = $coll->toArray();
        $newkeys = $coll->combineKeys($this->fixtures['emails']);
        $this->assertEquals(
            [
                'colleen.mills@example.org' => 'Chelsea',
                'hilda01@example.net'       => 'Adella',
                'leffler.ron@example.org'   => 'Monte',
                'jboyle@example.net'        => 'Maye',
                'esta10@example.com'        => 'Lottie',
                'fcormier@example.com'      => 'Don',
                'verda25@example.com'       => 'Dayton',
                'mparker@example.org'       => 'Kirk',
                'apollich@example.net'      => 'Troy',
                'hrussel@example.net'       => 'Nakia',
            ],
            $newkeys->toArray()
        );
        $this->assertNotSame($newkeys->toArray(), $orig);
        $this->assertSame(
            $coll->toArray(),
            $orig,
            'The original collection should not be affected by Collection::combineKeys().'
        );
    }

    /**
     * @expectedException \TypeError
     */
    public function testCombineKeysThrowsExceptionIfPassedNonTraversableNonArray(): void
    {
        $coll = Factory::create($this->fixtures['emails']);
        $coll->combineKeys('this is not traversable');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid input for combineKeys, number of items does not match.
     */
    public function testCombineKeysThrowsExceptionIfPassedTraversableWithBadCount(): void
    {
        $coll = Factory::create($this->fixtures['emails']);
        $coll->combineKeys([1, 2, 3]);
    }

    public function testCombineKeysAcceptsTraversable(): void
    {
        $coll        = Factory::create($this->fixtures['names']);
        $orig        = $coll->toArray();
        $iter        = $this->getIteratorForArray($this->fixtures['emails']);
        $keycombined = $coll->combineKeys($iter);
        $this->assertSame(
            [
                'colleen.mills@example.org' => 'Chelsea',
                'hilda01@example.net'       => 'Adella',
                'leffler.ron@example.org'   => 'Monte',
                'jboyle@example.net'        => 'Maye',
                'esta10@example.com'        => 'Lottie',
                'fcormier@example.com'      => 'Don',
                'verda25@example.com'       => 'Dayton',
                'mparker@example.org'       => 'Kirk',
                'apollich@example.net'      => 'Troy',
                'hrussel@example.net'       => 'Nakia',
            ],
            $keycombined->toArray()
        );
        $this->assertSame(
            $orig,
            $coll->toArray(),
            'Ensure that Collection::combineKeys does not change the original collection.'
        );
    }
}
