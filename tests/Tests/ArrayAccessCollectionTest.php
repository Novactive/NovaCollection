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
 * Class ArrayAccessCollectionTest.
 */
class ArrayAccessCollectionTest extends UnitTestCase
{
    public function testArrayAccessUnsetRemovesItemByKeyAndReturnsNull()
    {
        $exp  = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertTrue($coll->containsKey('2nd'));
        $removed = $coll->offsetUnset('2nd');
        $this->assertFalse($coll->containsKey('2nd'));
        $this->assertNull($removed, 'The ArrayAccess interface expects offsetUnset to have no return value.');
        $this->assertTrue($coll->containsKey('3rd'));
        unset($coll['3rd']);
        $this->assertFalse($coll->containsKey('3rd'));
    }

    public function testArrayAccessOffsetExistsAllowsIssetToWorkWithSquareBrackets()
    {
        // associative
        $exp  = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertTrue($coll->containsKey('2nd'));
        $this->assertTrue($coll->offsetExists('2nd'), 'Collection::offsetExists should return true if index exists.');
        $this->assertTrue(
            isset($coll['2nd']),
            'Collection::offsetExists should allow for the use of isset() on a collection using square brackets.'
        );
        $this->assertFalse($coll->containsKey('4th'));
        $this->assertFalse(
            $coll->offsetExists('4th'),
            'Collection::offsetExists should return false if index does not exist.'
        );
        $this->assertFalse(
            isset($coll['4th']),
            'Collection::offsetExists should allow for the use of isset() on a '.
            'collection using square brackets for an index that does not exist.'
        );

        // numeric
        $exp  = $this->fixtures['array'];
        $coll = Factory::create($exp);
        $this->assertTrue($coll->containsKey(1));
        $this->assertTrue(
            $coll->offsetExists(1),
            'Collection::offsetExists should return true if numeric offset exists.'
        );
        $this->assertTrue(
            isset($coll[1]),
            'Collection::offsetExists should allow for the use of isset() on a collection using '.
            'square brackets and numeric offset.'
        );
        $this->assertFalse($coll->containsKey(5));
        $this->assertFalse(
            $coll->offsetExists(5),
            'Collection::offsetExists should return false if numeric offset does not exist.'
        );
        $this->assertFalse(
            isset($coll[5]),
            'Collection::offsetExists should allow for the use of isset() on a collection using square brackets '.
            'for a numeric offset that does not exist.'
        );
    }

    public function testArrayAccessOffsetSetAllowsUseOfSquareBracketsForSetting()
    {
        // associative
        $exp  = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertFalse($coll->containsKey('foo'));
        $coll['foo'] = 'bar';
        $this->assertTrue($coll->containsKey('foo'));
        $this->assertEquals('bar', $coll->get('foo'));

        $this->assertFalse($coll->containsKey('boo'));
        $this->assertNull($coll->offsetSet('boo', 'far'), 'ArrayAccess offsetSet MUST NOT have a return value.');
        $this->assertTrue($coll->containsKey('boo'));
        $this->assertEquals('far', $coll->get('boo'));

        // numeric
        $exp  = $this->fixtures['array'];
        $coll = Factory::create($exp);
        $this->assertFalse($coll->containsKey(5));
        $coll[5] = 'bar';
        $this->assertTrue($coll->containsKey(5));
        $this->assertEquals('bar', $coll->get(5));

        $this->assertFalse($coll->containsKey('boo'));
        $this->assertNull($coll->offsetSet(6, 'far'), 'ArrayAccess offsetSet MUST NOT have a return value.');
        $this->assertTrue($coll->containsKey(6));
        $this->assertEquals('far', $coll->get(6));
    }

    public function testArrayAccessOffsetSetAllowsUseOfSquareBracketsForSettingWithoutIndex()
    {
        // associative
        $assoc = $this->fixtures['assoc'];
        $aColl = Factory::create($assoc);
        $this->assertFalse($aColl->containsKey(0));
        $aColl[] = 'test';
        $this->assertTrue($aColl->containsKey(0));

        // numeric
        $arr     = $this->fixtures['array'];
        $arrColl = Factory::create($arr);
        $this->assertTrue($arrColl->containsKey(0));
        $this->assertTrue($arrColl->containsKey(1));
        $this->assertTrue($arrColl->containsKey(2));
        $this->assertFalse($arrColl->containsKey(3));
        $arrColl[] = 'test';
        $this->assertTrue($arrColl->containsKey(3));
    }

    public function testArrayAccessOffsetGetAllowsUseOfSquareBracketsForGetting()
    {
        $exp  = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertEquals('first', $coll->offsetGet('1st'));
        $this->assertEquals('first', $coll['1st']);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unknown offset: foo
     */
    public function testArrayAccessOffsetGetThrowsExceptionIfIndexDoesNotExist()
    {
        $exp  = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $foo  = $coll['foo'];
    }
}
