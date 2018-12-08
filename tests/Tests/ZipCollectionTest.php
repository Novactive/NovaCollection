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

class ZipCollectionTest extends UnitTestCase
{
    public function testZipCollection(): void
    {
        $names = Factory::create($this->fixtures['names'])->keep(0, 3);
        if (!$names instanceof Perfs\ArrayMethodCollection) {
            $zip = $names->zip(Factory::create($this->fixtures['array']));

            $expected = [
                ['Chelsea', 'first'],
                ['Adella', 'second'],
                ['Monte', 'third'],
            ];

            $this->assertEquals($expected, $zip->toArray());
        }
    }

    public function testNotZipCollection(): void
    {
        $names = Factory::create($this->fixtures['names'])->keep(0, 3);
        if (!$names instanceof Perfs\ArrayMethodCollection) {
            $zip = $names->zip(Factory::create($this->fixtures['array']));

            $expected = [
                ['Chelsea', 'firdst'],
                ['Adella', 'secodnd'],
                ['Monte', 'thidrd'],
            ];

            $this->assertNotEquals($expected, $zip->toArray());
        }
    }
}
