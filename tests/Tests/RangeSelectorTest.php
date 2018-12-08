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
 * Class RangeSelectorTest.
 */
class RangeSelectorTest extends UnitTestCase
{
    public function badArgumentsProvider()
    {
        return [
            [['asda', 'sdsd']],
            [['bdsd']],
        ];
    }

    public function argumentsProvider()
    {
        $resultSet1 = [0 => '2 mois'];
        $resultSet2 = [
            0 => '3 mois',
            1 => '4 mois',
            2 => '5 mois',
            3 => '6 mois',
        ];

        $resultSet3 = [
            0 => '6 mois',
            1 => '5 mois',
            2 => '4 mois',
            3 => '3 mois',
        ];

        return [
            [['1..1'], $resultSet1],
            [['1:1'], $resultSet1],
            [['1-1'], $resultSet1],
            [['1,1'], $resultSet1],
            [[[1, 1]], $resultSet1],

            [['2..5'], $resultSet2],
            [['2:5'], $resultSet2],
            [['2-5'], $resultSet2],
            [[[2, 5]], $resultSet2],
            [[2, 3, 4, 5], $resultSet2],

            [['5..2'], $resultSet3],
            [['5:2'], $resultSet3],
            [['5-2'], $resultSet3],
            [[[5, 2]], $resultSet3],
            [[5, 4, 3, 2], $resultSet3],

            [[1, 1], [0 => '2 mois', 1 => '2 mois']],
            [[4, 2], [0 => '5 mois', 1 => '3 mois']],
            [[2, 4], [0 => '3 mois', 1 => '5 mois']],
            [[0, 10], [0 => '1 mois', 1 => '11 mois']],

            [
                [0, 10, '1-2', [2, 4]],
                [
                    0 => '1 mois',
                    1 => '11 mois', 2 => '2 mois', 3 => '3 mois', 4 => '3 mois', 5 => '4 mois', 6 => '5 mois',
                ],
            ],
            [
                [0, 1, 2, '4-2', '3,2;5-2;10'],
                [
                    0 => '1 mois',
                    1 => '2 mois', 2 => '3 mois', 3 => '5 mois', 4 => '4 mois', 5 => '3 mois', 6 => '4 mois',
                    7 => '5 mois', 8 => '6 mois', 9 => '5 mois', 10 => '4 mois', 11 => '3 mois', 12 => '11 mois',
                ],
            ],
            [
                ['5..2;5..5;1..3;9:2;7', 1, 2],
                [
                    0  => '6 mois', 1 => '5 mois', 2 => '4 mois', 3 => '3 mois', 4 => '6 mois', 5 => '2 mois',
                    6  => '3 mois', 7 => '4 mois', 8 => '10 mois', 9 => '9 mois', 10 => '8 mois', 11 => '7 mois',
                    12 => '6 mois', 13 => '5 mois', 14 => '4 mois', 15 => '3 mois', 16 => '8 mois', 17 => '2 mois',
                    18 => '3 mois',
                ],
            ],
            [
                ['5..2;5..5', [5, 2], 3, 10, 1, '1,3', '1..3;9:2;7', 1, 2],
                [
                    0  => '6 mois', 1 => '5 mois', 2 => '4 mois', 3 => '3 mois',
                    4  => '6 mois', 5 => '6 mois', 6 => '5 mois', 7 => '4 mois',
                    8  => '3 mois', 9 => '4 mois', 10 => '11 mois', 11 => '2 mois', 12 => '2 mois',
                    13 => '3 mois', 14 => '4 mois', 15 => '2 mois', 16 => '3 mois', 17 => '4 mois',
                    18 => '10 mois', 19 => '9 mois', 20 => '8 mois', 21 => '7 mois', 22 => '6 mois',
                    23 => '5 mois', 24 => '4 mois', 25 => '3 mois', 26 => '8 mois', 27 => '2 mois', 28 => '3 mois',
                ],
            ],
        ];
    }

    /**
     * @dataProvider argumentsProvider
     */
    public function testRangeSelector($args, $expected)
    {
        $list             = [
            '1 mois', '2 mois', '3 mois', '4 mois', '5 mois', '6 mois', '7 mois', '8 mois', '9 mois', '10 mois',
            '11 mois',
            '1 an', "Plus d'un an", 'Plus de deux ans', 'Indéfini',
        ];
        $testedCollection = Factory::create($list);
        $results          = call_user_func_array($testedCollection, $args);
        $this->assertEquals($expected, $results->toArray());
        $this->assertNotSame($testedCollection, $results);
    }
}
