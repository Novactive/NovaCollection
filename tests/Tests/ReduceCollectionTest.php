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

class ReduceCollectionTest extends UnitTestCase
{
    public function testReduceIterativelyReducesCollectionToASingleValue(): void
    {
        $coll = Factory::create($this->fixtures['names']);

        $count = function ($carry, $val, $key = null) {
            return ++$carry;
        };

        $concat = function ($carry, $val, $key = null) {
            return $carry.$val;
        };

        $concateven = function ($carry, $val, $key) {
            if (0 == $key % 2) {
                $carry .= $val;
            }

            return $carry;
        };

        $this->assertEquals(10, $coll->reduce($count));
        $this->assertEquals('ChelseaAdellaMonteMayeLottieDonDaytonKirkTroyNakia', $coll->reduce($concat));

        if (!$coll instanceof Perfs\ArrayMethodCollection) {
            $this->assertEquals('ChelseaMonteLottieDaytonTroy', $coll->reduce($concateven));
        }
    }

    public function testImplodeCollection(): void
    {
        $coll = Factory::create($this->fixtures['names']);

        $return = $coll->implode(';');

        $reduction = function ($carry, $val, $key = null) {
            if (!empty($carry)) {
                return $carry.';'.$val;
            }

            return $val;
        };

        $return2 = $coll->reduce($reduction);

        $this->assertEquals($return, $return2);
    }
}
