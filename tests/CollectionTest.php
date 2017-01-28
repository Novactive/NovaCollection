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
 * Class CollectionTest.
 */
class CollectionTest extends UnitTestCase
{
    public function testInstantiateCollectionWithNoParams()
    {
        $coll = Factory::create();
        $this->assertInstanceOf(Collection::class, $coll);
    }

    public function testCollectionToArrayConvertsItemsToArray()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertInstanceOf(Collection::class, $coll);
        $this->assertEquals(
            ['Chelsea', 'Adella', 'Monte', 'Maye', 'Lottie', 'Don', 'Dayton', 'Kirk', 'Troy', 'Nakia'],
            $coll->toArray()
        );
    }

    public function testFirstReturnsFirstItemInCollection()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals('Chelsea', $coll->first());
    }

    public function testLastReturnsLastItemInCollection()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals('Nakia', $coll->last());
    }

    public function testCurrentReturnsCurrentValue()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals('Chelsea', $coll->current());
    }

    public function testNextMovesCollectionInternalPointer()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals(
            'Chelsea',
            $coll->current(),
            'Initial call to current() should return first item in collection.'
        );
        $this->assertEquals(
            'Chelsea',
            $coll->current(),
            'Subsequent calls to current() should continue to return the same value.'
        );
        $coll->next();
        $this->assertEquals(
            'Adella',
            $coll->current(),
            'After calling next(), current() should return the next item in the collection.'
        );
        $coll->next();
        $this->assertEquals(
            'Monte',
            $coll->current(),
            'Subsequent calls to next() and current() should return the next item in the collection.'
        );
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $this->assertEquals('Nakia', $coll->current(), 'We should now be at the last item in the collection.');
        $coll->next();
        $this->assertFalse(
            $coll->current(),
            "Subsequent calls to current() should return false because we have next'd past the end of the collection."
        );
        $coll->next();
        $this->assertFalse(
            $coll->current(),
            "Subsequent calls to current() should return false because we have next'd past the end of the collection."
        );
    }

    public function testKeyReturnsCurrentKeyInCollection()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertSame(0, $coll->key(), 'Initial call to key() should return first item in collection.');
        $this->assertSame(0, $coll->key(), 'Subsequent calls to key() should continue to return the same value.');
        $coll->next();
        $this->assertSame(
            1,
            $coll->key(),
            'After calling next(), key() should return the next item in the collection.'
        );
        $coll->next();
        $this->assertSame(
            2,
            $coll->key(),
            'Subsequent calls to next() and key() should return the next item in the collection.'
        );
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $this->assertSame(9, $coll->key(), 'We should now be at the last item in the collection.');
        $coll->next();
        $this->assertNull(
            $coll->key(),
            'Subsequent calls to key() should continue to return null because'.
            " we have next'd past the end of the collection."
        );
        $coll->next();
        $this->assertNull(
            $coll->key(),
            'Subsequent calls to key() should continue to return null because'.
            " we have next'd past the end of the collection."
        );
    }

    public function testValidReturnsFalseWhenCollectionHasBeenIteratedBeyondItsLastItem()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertTrue($coll->valid(), 'Initial call to valid() should always return true.');
        $coll->next();
        $this->assertTrue($coll->valid(), 'Subsequent calls to valid() should continue to return true.');
        $coll->next();
        $this->assertTrue($coll->valid(), 'Subsequent calls to valid() should continue to return true.');
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $this->assertTrue($coll->valid(), 'When at the last item in the collection, valid() should still return true.');
        $coll->next();
        $this->assertFalse(
            $coll->valid(),
            'Finally, valid() should return false because we have iterated beyond the end of the collection.'
        );
    }

    public function testRewindWillReturnInternalPointerToItsInitialPosition()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals('Chelsea', $coll->first());
        $this->assertTrue($coll->valid(), 'Initial call to valid() should always return true.');
        $coll->next();
        $this->assertTrue($coll->valid(), 'Subsequent calls to valid() should continue to return true.');
        $coll->next();
        $this->assertTrue($coll->valid(), 'Subsequent calls to valid() should continue to return true.');
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $coll->next();
        $this->assertTrue($coll->valid(), 'When at the last item in the collection, valid() should still return true.');
        $this->assertEquals($coll->last(), $coll->current(), 'Current value should be the last value now.');
        $coll->next();
        $this->assertFalse(
            $coll->valid(),
            'Finally, valid() should return false because we have iterated beyond the end of the collection.'
        );
        $this->assertNull($coll->rewind(), 'Rewind MUST NOT have a return value.');
        $this->assertTrue(
            $coll->valid(),
            'The rewind() method should have returned us to the beginning now so valid() should return true.'
        );
        $this->assertEquals(
            $coll->first(),
            $coll->current(),
            'The rewind() method should have returned us to the beginning now so'.
            ' current() should return the first item in the collection.'
        );

        $coll->next();
        $coll2 = clone $coll;
        $this->assertEquals($coll2->first(), $coll2->current(), 'A clone of a collection should be reset.');
    }

    public function testInstantiationShouldRewindArrayArgsInternalPointer()
    {
        $arr = $this->fixtures['names'];
        $this->assertEquals('Chelsea', current($arr));
        next($arr);
        $this->assertEquals('Adella', current($arr));
        $coll = Factory::create($arr);
        $this->assertEquals('Chelsea', $coll->current());
    }

    public function testCountReturnsTotalCollectionCount()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertCount(10, $coll);
        $this->assertEquals(10, $coll->count());
        $this->assertEquals(10, count($coll));
    }


    public function testAddAppendsItemToCollectionWithNextNumericIndex()
    {
        $array = new Collection($this->fixtures['array']);
        $this->assertEquals([
            0 => 'first',
            1 => 'second',
            2 => 'third'
        ], $array->toArray());
        $array->add('fourth');
        $this->assertSame([
            0 => 'first',
            1 => 'second',
            2 => 'third',
            3 => 'fourth'
        ], $array->toArray());
        $array->add('fifth');
        $this->assertSame([
            0 => 'first',
            1 => 'second',
            2 => 'third',
            3 => 'fourth',
            4 => 'fifth'
        ], $array->toArray());

        $assoc = new Collection($this->fixtures['assoc']);
        $this->assertEquals([
            '1st' => 'first',
            '2nd' => 'second',
            '3rd' => 'third'
        ], $assoc->toArray());
        $assoc->add('fourth');
        $this->assertSame([
            '1st' => 'first',
            '2nd' => 'second',
            '3rd' => 'third',
            0 => 'fourth'
        ], $assoc->toArray());
        $assoc->add('fifth');
        $this->assertSame([
            '1st' => 'first',
            '2nd' => 'second',
            '3rd' => 'third',
            0 => 'fourth',
            1 => 'fifth'
        ], $assoc->toArray());
    }

    // I decided not to test this because I don't agree with its implementaion. see issue #3 on Github -lv
//    public function testAddAppendsMultipleItemsToCollectionIfPassedAnythingTraversable()
//    {
//        $names = $this->fixtures['names'];
//        $array = $this->fixtures['array'];
//        $coll = new Collection($names);
//        $this->assertEquals($names, $coll->toArray());
//        $coll->add($array);
//        $this->assertEquals(, $coll->toArray());
//    }

    public function testSetWillSetValueOnCollectionInPlace()
    {
        $exp = $this->fixtures['assoc'];
        $coll = new Collection($exp);
        $this->assertEquals($exp, $coll->toArray());
        $this->assertSame($coll, $coll->set('1st', 'worst'), "Collection::set() should return the collection itself.");
        $exp['1st'] = 'worst';
        $this->assertEquals($exp, $coll->toArray());
        $coll->set('first','worst');
        $exp['first'] = 'worst';
        $this->assertEquals($exp, $coll->toArray());
    }

//    public function testRemoveRemovesItemByKey()
//    {
//        $exp = $this->fixtures['assoc'];
//        $coll = new Collection($exp);
//        $this->assertTrue($coll->containsKey('2nd'));
//        $removed = $coll->remove('2nd');
//        $this->assertFalse($coll->containsKey('2nd'));
//        $this->assertEquals('second', $removed, 'Removed should return the removed item.');
//        $this->assertNull($coll->remove('2nd'), 'Attempting to remove an item that does not exist should return null.');
//    }

    public function testGetReturnsItemIfItExists()
    {
        $exp = $this->fixtures['assoc'];
        $coll = new Collection($exp);
        $this->assertEquals('second', $coll->get('2nd'));
        $coll->remove('2nd');
        $this->assertNull($coll->get('2nd'));
        $this->assertEquals('default', $coll->get('2nd','default'), 'If a default is provided, Collection::get() should return it if no item is found.');
    }

    public function testHasReturnsTrueIfItemExistsByKey()
    {
        $exp = $this->fixtures['assoc'];
        $coll = new Collection($exp);
        $this->assertTrue($coll->containsKey('2nd'));
        $coll->remove('2nd');
        $this->assertFalse($coll->containsKey('2nd'));
    }

    public function testArrayAccessUnsetRemovesItemByKeyAndReturnsNull()
    {
        $exp = $this->fixtures['assoc'];
        $coll = new Collection($exp);
        $this->assertTrue($coll->containsKey('2nd'));
        $removed = $coll->offsetUnset('2nd');
        $this->assertFalse($coll->containsKey('2nd'));
        //$this->assertNull($removed, 'The ArrayAccess interface expects offsetUnset to have no return value.');
        $this->assertTrue($coll->containsKey('3rd'));
        unset($coll['3rd']);
        $this->assertFalse($coll->containsKey('3rd'));
    }

    public function testArrayAccessOffsetExistsAllowsIssetToWorkWithSquareBrackets()
    {
        // associative
        $exp = $this->fixtures['assoc'];
        $coll = new Collection($exp);
        $this->assertTrue($coll->containsKey('2nd'));
        $this->assertTrue($coll->offsetExists('2nd'), "Collection::offsetExists should return true if index exists.");
        $this->assertTrue(isset($coll['2nd']), "Collection::offsetExists should allow for the use of isset() on a collection using square brackets.");
        $this->assertFalse($coll->containsKey('4th'));
        $this->assertFalse($coll->offsetExists('4th'), "Collection::offsetExists should return false if index does not exist.");
        $this->assertFalse(isset($coll['4th']), "Collection::offsetExists should allow for the use of isset() on a collection using square brackets for an index that does not exist.");

        // numeric
        $exp = $this->fixtures['array'];
        $coll = new Collection($exp);
        $this->assertTrue($coll->containsKey(1));
        $this->assertTrue($coll->offsetExists(1), "Collection::offsetExists should return true if numeric offset exists.");
        $this->assertTrue(isset($coll[1]), "Collection::offsetExists should allow for the use of isset() on a collection using square brackets and numeric offset.");
        $this->assertFalse($coll->containsKey(5));
        $this->assertFalse($coll->offsetExists(5), "Collection::offsetExists should return false if numeric offset does not exist.");
        $this->assertFalse(isset($coll[5]), "Collection::offsetExists should allow for the use of isset() on a collection using square brackets for a numeric offset that does not exist.");
    }

    public function testArrayAccessOffsetSetAllowsUseOfSquareBracketsForSetting()
    {
        // associative
        $exp = $this->fixtures['assoc'];
        $coll = new Collection($exp);
        $this->assertFalse($coll->containsKey('foo'));
        $coll['foo'] = 'bar';
        $this->assertTrue($coll->containsKey('foo'));
        $this->assertEquals('bar', $coll->get('foo'));

        $this->assertFalse($coll->containsKey('boo'));
        $this->assertTrue($coll->offsetSet('boo', 'far'), "ArrayAccess offsetSet MUST NOT have a return value.");
        $this->assertTrue($coll->containsKey('boo'));
        $this->assertEquals('far', $coll->get('boo'));

        // numeric
        $exp = $this->fixtures['array'];
        $coll = new Collection($exp);
        $this->assertFalse($coll->containsKey(5));
        $coll[5] = 'bar';
        $this->assertTrue($coll->containsKey(5));
        $this->assertEquals('bar', $coll->get(5));

        $this->assertFalse($coll->containsKey('boo'));
        $this->assertTrue($coll->offsetSet(6, 'far'), "ArrayAccess offsetSet MUST NOT have a return value.");
        $this->assertTrue($coll->containsKey(6));
        $this->assertEquals('far', $coll->get(6));
    }

    public function testArrayAccessOffsetSetAllowsUseOfSquareBracketsForSettingWithoutIndex()
    {

    }

    public function testArrayAccessOffsetGetAllowsUseOfSquareBracketsForGetting()
    {
        $exp = $this->fixtures['assoc'];
        $coll = new Collection($exp);
        $this->assertEquals('first', $coll->offsetGet('1st'));
        $this->assertEquals('first', $coll['1st']);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Unknown offset: foo
     */
    public function testArrayAccessOffsetGetThrowsExceptionIfIndexDoesNotExist()
    {
        $exp = $this->fixtures['assoc'];
        $coll = new Collection($exp);
        $foo = $coll['foo'];
    }

    public function testEachLoopsOverEveryItemInCollectionCallingCallback()
    {
        $coll = new Collection($this->fixtures['names']);
        $namesCount = count($this->fixtures['names']);
        $recorder = $this->getMethodCallRecorderForCount($namesCount);

        // this will test that touch() is called $namesCount times
        $return = $coll->each(function($val, $key, $iter) use ($recorder) {
            $this->assertTrue(is_string($val));
            $this->assertTrue(is_numeric($key));
            $this->assertTrue(is_numeric($iter));
            $recorder->touch();
        });
        $this->assertSame($coll, $return, "Collection::each() should return the collection itself.");
    }

    public function testMapReturnsNewCollectionWithTransformedConstituents()
    {
        $coll = new Collection($this->fixtures['names']);
        $transformed = $coll->map(function($val, $key) {
            return $val . $key;
        });
        $this->assertInstanceOf(Collection::class, $transformed);
        $this->assertNotSame($coll, $transformed);
        $this->assertEquals([
            'Chelsea0',
            'Adella1',
            'Monte2',
            'Maye3',
            'Lottie4',
            'Don5',
            'Dayton6',
            'Kirk7',
            'Troy8',
            'Nakia9'
        ], $transformed->toArray());
    }

    public function testTransformTransformsCollectionInPlace()
    {
        $coll = new Collection($this->fixtures['names']);
        $transformed = $coll->transform(function($val, $key) {
            return $val . $key;
        });
        $this->assertInstanceOf(Collection::class, $transformed);
        $this->assertSame($coll, $transformed);
        $this->assertEquals([
            'Chelsea0',
            'Adella1',
            'Monte2',
            'Maye3',
            'Lottie4',
            'Don5',
            'Dayton6',
            'Kirk7',
            'Troy8',
            'Nakia9'
        ], $coll->toArray());
    }

    public function testFilterReturnsNewCollectionFilteredByPredicateCallback()
    {
        $predicate = function($val, $key) {
            return strlen($val) > 4;
        };
        $coll = new Collection($this->fixtures['names']);
        $this->assertCount(10, $coll);
        $filtered = $coll->filter($predicate);
        $this->assertInstanceOf(Collection::class, $filtered);
        $this->assertNotSame($coll, $filtered);
        $this->assertCount(10, $coll);
        $this->assertCount(6, $filtered);
        $this->assertEquals([
            0 => 'Chelsea',
            1 => 'Adella',
            2 => 'Monte',
            4 => 'Lottie',
            6 => 'Dayton',
            9 => 'Nakia'
        ], $filtered->toArray());
    }
}
