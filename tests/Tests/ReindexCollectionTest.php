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
 * Class ReindexCollectionTest.
 */
class ReindexCollectionTest extends UnitTestCase
{

    public function testReIndexPerformsCombineKeysInPlace()
    {
        $coll = Factory::create($this->fixtures['names']);

        $this->assertEquals(
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

        $return = $coll->reindex($this->fixtures['emails']);
        $this->assertSame($coll, $return);
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
            $return->toArray()
        );
    }
}
