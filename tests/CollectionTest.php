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

use InvalidArgumentException;
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
        $array = Factory::create($this->fixtures['array']);
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

        $assoc = Factory::create($this->fixtures['assoc']);
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
//        $coll = Factory::create($names);
//        $this->assertEquals($names, $coll->toArray());
//        $coll->add($array);
//        $this->assertEquals(, $coll->toArray());
//    }

    public function testSetWillSetValueOnCollectionInPlace()
    {
        $exp = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertEquals($exp, $coll->toArray());
        $this->assertSame($coll, $coll->set('1st', 'worst'), "Collection::set() should return the collection itself.");
        $exp['1st'] = 'worst';
        $this->assertEquals($exp, $coll->toArray());
        $coll->set('first','worst');
        $exp['first'] = 'worst';
        $this->assertEquals($exp, $coll->toArray());
    }

    public function testRemoveRemovesItemByKey()
    {
        $exp = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertTrue($coll->containsKey('2nd'));
        $removed = $coll->remove('2nd');
        $this->assertFalse($coll->containsKey('2nd'));
        $this->assertSame($coll, $removed, 'Remove method should return the collection itself.');
        $return = $coll->remove('2nd');
        $this->assertSame($coll, $return, 'Attempting to remove an item that does not exist should still return the collection itself.');
    }

    public function testGetReturnsItemIfItExists()
    {
        $exp = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertEquals('second', $coll->get('2nd'));
        $coll->remove('2nd');
        $this->assertNull($coll->get('2nd'));
        $this->assertEquals('default', $coll->get('2nd','default'), 'If a default is provided, Collection::get() should return it if no item is found.');
    }

    public function testContainsKeyReturnsTrueIfItemExistsByKey()
    {
        $exp = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertTrue($coll->containsKey('2nd'));
        $coll->remove('2nd');
        $this->assertFalse($coll->containsKey('2nd'));
    }

    public function testArrayAccessUnsetRemovesItemByKeyAndReturnsNull()
    {
        $exp = $this->fixtures['assoc'];
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
        $exp = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
        $this->assertTrue($coll->containsKey('2nd'));
        $this->assertTrue($coll->offsetExists('2nd'), "Collection::offsetExists should return true if index exists.");
        $this->assertTrue(isset($coll['2nd']), "Collection::offsetExists should allow for the use of isset() on a collection using square brackets.");
        $this->assertFalse($coll->containsKey('4th'));
        $this->assertFalse($coll->offsetExists('4th'), "Collection::offsetExists should return false if index does not exist.");
        $this->assertFalse(isset($coll['4th']), "Collection::offsetExists should allow for the use of isset() on a collection using square brackets for an index that does not exist.");

        // numeric
        $exp = $this->fixtures['array'];
        $coll = Factory::create($exp);
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
        $coll = Factory::create($exp);
        $this->assertFalse($coll->containsKey('foo'));
        $coll['foo'] = 'bar';
        $this->assertTrue($coll->containsKey('foo'));
        $this->assertEquals('bar', $coll->get('foo'));

        $this->assertFalse($coll->containsKey('boo'));
        $this->assertNull($coll->offsetSet('boo', 'far'), "ArrayAccess offsetSet MUST NOT have a return value.");
        $this->assertTrue($coll->containsKey('boo'));
        $this->assertEquals('far', $coll->get('boo'));

        // numeric
        $exp = $this->fixtures['array'];
        $coll = Factory::create($exp);
        $this->assertFalse($coll->containsKey(5));
        $coll[5] = 'bar';
        $this->assertTrue($coll->containsKey(5));
        $this->assertEquals('bar', $coll->get(5));

        $this->assertFalse($coll->containsKey('boo'));
        $this->assertNull($coll->offsetSet(6, 'far'), "ArrayAccess offsetSet MUST NOT have a return value.");
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
        $arr = $this->fixtures['array'];
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
        $exp = $this->fixtures['assoc'];
        $coll = Factory::create($exp);
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
        $coll = Factory::create($exp);
        $foo = $coll['foo'];
    }

    public function testEachLoopsOverEveryItemInCollectionCallingCallback()
    {
        $coll = Factory::create($this->fixtures['names']);
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
        $coll = Factory::create($this->fixtures['names']);
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
        $coll = Factory::create($this->fixtures['names']);
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
        $coll = Factory::create($this->fixtures['names']);
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

    public function testPruneFiltersCollectionInPlace()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertCount(10, $coll);
        $this->assertSame($coll, $coll->prune(function($value, $key) {
            return $key % 2 == 0;
        }));
        $this->assertCount(5, $coll);
        $this->assertSame($coll, $coll->prune(function($value, $key) {
            return strlen($value) >= 6;
        }));
        $this->assertCount(3, $coll);
    }

    public function testContainsReturnsTrueIfValueFoundInCollection()
    {
        $coll = Factory::create($this->fixtures['assoc']);
        $this->assertTrue($coll->contains('first'));
        $this->assertTrue($coll->contains('second'));
        $this->assertTrue($coll->contains('third'));
        $this->assertFalse($coll->contains('fourth'));
        $this->assertFalse($coll->contains('foo'));
        $coll->add('foo');
        $this->assertTrue($coll->contains('foo'));

        $this->assertFalse($coll->contains(100));
        $coll->add(100);
        $this->assertFalse($coll->contains('100'), 'Collection::contains method uses strict type comparison so that 100 !== "100"');
        // @todo I think we should add a second argument to contains() that does an additional check against the index so taht you can check whether the collection contains a certain value at a certain index.
        // @todo I think we should also allow for $value argument to be a callable. When it is a callable, it should be used instead of in_array to determine whether the collection contains whatever...
        $this->assertTrue($coll->contains(100));
    }

    public function testPullRemovesItemFromCollectionAndReturnsIt()
    {
        $coll = Factory::create($this->fixtures['assoc']);
        $this->assertCount(3, $coll);
        $this->assertTrue($coll->containsKey('2nd'));
        $this->assertSame('second', $pulled = $coll->pull('2nd'), "Collection::pull() should return an item by key and return it.");
        $this->assertFalse($coll->containsKey('2nd'));
        $this->assertCount(2, $coll);
        $this->assertNull($coll->pull('2nd'), "Collection::pull() should return null if key does not exist.");
    }

    public function testCombineReturnsCollectionWithExistingKeysAndIncomingValues()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertSame([
            'Chelsea',
            'Adella',
            'Monte',
            'Maye',
            'Lottie',
            'Don',
            'Dayton',
            'Kirk',
            'Troy',
            'Nakia'
        ], $orig = $coll->toArray());
        $newcoll = $coll->combine($this->fixtures['emails']);
        $this->assertNotSame($coll, $newcoll);
        $this->assertSame([
            'Chelsea' => 'colleen.mills@example.org',
            'Adella' => 'hilda01@example.net',
            'Monte' => 'leffler.ron@example.org',
            'Maye' => 'jboyle@example.net',
            'Lottie' => 'esta10@example.com',
            'Don' => 'fcormier@example.com',
            'Dayton' => 'verda25@example.com',
            'Kirk' => 'mparker@example.org',
            'Troy' => 'apollich@example.net',
            'Nakia' => 'hrussel@example.net'
        ], $newcoll->toArray());
        $this->assertSame($orig, $coll->toArray(), 'Ensure that Collection::combine() has not changed the original collection.');
    }

    public function testCombineAcceptsTraversable()
    {
        $stub = $this->getIteratorForArray($this->fixtures['emails']);
        $coll = Factory::create($this->fixtures['names']);
        $this->assertSame([
            'Chelsea',
            'Adella',
            'Monte',
            'Maye',
            'Lottie',
            'Don',
            'Dayton',
            'Kirk',
            'Troy',
            'Nakia'
        ], $coll->toArray());
        $this->assertSame([
            'colleen.mills@example.org',
            'hilda01@example.net',
            'leffler.ron@example.org',
            'jboyle@example.net',
            'esta10@example.com',
            'fcormier@example.com',
            'verda25@example.com',
            'mparker@example.org',
            'apollich@example.net',
            'hrussel@example.net'
        ], Factory::getArrayForItems($stub));
        $orig = $coll->toArray();
        $return = $coll->combine($stub);
        $this->assertEquals([
            'Chelsea' => 'colleen.mills@example.org',
            'Adella' => 'hilda01@example.net',
            'Monte' => 'leffler.ron@example.org',
            'Maye' => 'jboyle@example.net',
            'Lottie' => 'esta10@example.com',
            'Don' => 'fcormier@example.com',
            'Dayton' => 'verda25@example.com',
            'Kirk' => 'mparker@example.org',
            'Troy' => 'apollich@example.net',
            'Nakia' => 'hrussel@example.net'
        ], $return->toArray());
        $this->assertSame($orig, $coll->toArray(), 'Ensure that Collection::combine() has not changed the original collection.');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid input type for combine.
     */
    public function testCombineThrowsExceptionIfInvalidInput()
    {
        $coll = Factory::create($this->fixtures['names']);
        $coll->combine('not an array');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid input for combine, number of items does not match.
     */
    public function testCombineThrowsExceptionIfIncomingTraversableCountIsNotSameAsCollection()
    {
        $coll = Factory::create($this->fixtures['names']);
        $coll->combine([1,2,3]);
    }

    public function testReplaceCombinesInPlace()
    {
        $coll = Factory::create($this->fixtures['names']);
        $return = $coll->replace($this->fixtures['emails']);
        $this->assertSame($coll, $return);
        $this->assertEquals([
            'Chelsea' => 'colleen.mills@example.org',
            'Adella' => 'hilda01@example.net',
            'Monte' => 'leffler.ron@example.org',
            'Maye' => 'jboyle@example.net',
            'Lottie' => 'esta10@example.com',
            'Don' => 'fcormier@example.com',
            'Dayton' => 'verda25@example.com',
            'Kirk' => 'mparker@example.org',
            'Troy' => 'apollich@example.net',
            'Nakia' => 'hrussel@example.net'
        ], $coll->toArray());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid input type for replace.
     */
    public function testReplaceThrowsExceptionIfInvalidInput()
    {
        $coll = Factory::create($this->fixtures['names']);
        $coll->replace('not an array');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid input for replace, number of items does not match.
     */
    public function testReplaceThrowsExceptionIfIncomingTraversableCountIsNotSameAsCollection()
    {
        $coll = Factory::create($this->fixtures['names']);
        $coll->replace([1,2,3]);
    }

    public function testCombineKeysUsesIncomingTraversableAsKeysForCollectionsValues()
    {
        $coll = Factory::create($this->fixtures['names']);
        $orig = $coll->toArray();
        $newkeys = $coll->combineKeys($this->fixtures['emails']);
        $this->assertEquals([
            "colleen.mills@example.org" => "Chelsea",
            "hilda01@example.net" => "Adella",
            "leffler.ron@example.org" => "Monte",
            "jboyle@example.net" => "Maye",
            "esta10@example.com" => "Lottie",
            "fcormier@example.com" => "Don",
            "verda25@example.com" => "Dayton",
            "mparker@example.org" => "Kirk",
            "apollich@example.net" => "Troy",
            "hrussel@example.net" => "Nakia"
        ], $newkeys->toArray());
        $this->assertNotSame($newkeys->toArray(), $orig);
        $this->assertSame($coll->toArray(), $orig, "The original collection should not be affected by Collection::combineKeys().");
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid input type for combineKeys.
     */
    public function testCombineKeysThrowsExceptionIfPassedNonTraversableNonArray()
    {
        $coll = Factory::create($this->fixtures['emails']);
        $coll->combineKeys('this is not traversable');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid input for combineKeys, number of items does not match.
     */
    public function testCombineKeysThrowsExceptionIfPassedTraversableWithBadCount()
    {
        $coll = Factory::create($this->fixtures['emails']);
        $coll->combineKeys([1,2,3]);
    }

    public function testCombineKeysAcceptsTraversable()
    {
        $coll = Factory::create($this->fixtures['names']);
        $orig = $coll->toArray();
        $iter = $this->getIteratorForArray($this->fixtures['emails']);
        $keycombined = $coll->combineKeys($iter);
        $this->assertSame([
            "colleen.mills@example.org" => "Chelsea",
            "hilda01@example.net" => "Adella",
            "leffler.ron@example.org" => "Monte",
            "jboyle@example.net" => "Maye",
            "esta10@example.com" => "Lottie",
            "fcormier@example.com" => "Don",
            "verda25@example.com" => "Dayton",
            "mparker@example.org" => "Kirk",
            "apollich@example.net" => "Troy",
            "hrussel@example.net" => "Nakia"
        ], $keycombined->toArray());
        $this->assertSame($orig, $coll->toArray(), "Ensure that Collection::combineKeys does not change the original collection.");
    }

    public function testReIndexPerformsCombineKeysInPlace()
    {
        $coll = Factory::create($this->fixtures['names']);
        $this->assertEquals([
            'Chelsea',
            'Adella',
            'Monte',
            'Maye',
            'Lottie',
            'Don',
            'Dayton',
            'Kirk',
            'Troy',
            'Nakia'
        ], $orig = $coll->toArray());
        $return = $coll->reindex($this->fixtures['emails']);
        $this->assertSame($coll, $return);
        $this->assertSame([
            "colleen.mills@example.org" => "Chelsea",
            "hilda01@example.net" => "Adella",
            "leffler.ron@example.org" => "Monte",
            "jboyle@example.net" => "Maye",
            "esta10@example.com" => "Lottie",
            "fcormier@example.com" => "Don",
            "verda25@example.com" => "Dayton",
            "mparker@example.org" => "Kirk",
            "apollich@example.net" => "Troy",
            "hrussel@example.net" => "Nakia"
        ], $return->toArray());
    }
}
