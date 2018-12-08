| Branch   | Travis | Sensio Insight | Scrutinizer Quality | Scrutinizer Coverage  |
|:--------:|:------:|:--------------:|:-------------------:|:---------------------:|
| develop  | [![Build Status](https://travis-ci.org/Novactive/NovaCollection.svg?branch=develop)](https://travis-ci.org/Novactive/NovaCollection) | [![SensioLabsInsight](https://insight.sensiolabs.com/projects/0d53485d-5fbf-46a0-a1c2-c5c879a78b1e/mini.png)](https://insight.sensiolabs.com/projects/0d53485d-5fbf-46a0-a1c2-c5c879a78b1e) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Novactive/NovaCollection/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Novactive/NovaCollection/?branch=develop) |  [![Code Coverage](https://scrutinizer-ci.com/g/Novactive/NovaCollection/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Novactive/NovaCollection/?branch=develop)
| master   | [![Build Status](https://travis-ci.org/Novactive/NovaCollection.svg?branch=master)](https://travis-ci.org/Novactive/NovaCollection)  | [![SensioLabsInsight](https://insight.sensiolabs.com/projects/8276b281-ea82-423c-8725-b6e9163260de/mini.png)](https://insight.sensiolabs.com/projects/8276b281-ea82-423c-8725-b6e9163260de)|  [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Novactive/NovaCollection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Novactive/NovaCollection/?branch=master) |  [![Code Coverage](https://scrutinizer-ci.com/g/Novactive/NovaCollection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Novactive/NovaCollection/?branch=master)

# Nova Collection

Because we did not find any *Collection* for PHP as simple as we wanted, we did ours.

## Simpler, Better, Faster, Stronger

Using *array_** functions against *foreach* is not an easy pick. As this library is made to manage low level actions.
We wanted it to be fast, then we compared and we implemented the best choices. (have a look: [PHP7.2](https://i.imgur.com/JUUo6EE.png))

Fully unit tested it is reliable.

## Methods

### Advanced Selection by range

```php
$collection = NovaCollection([0,1,2,3,4,5,6,7,8,9,10]);
$collection([2,4], 1, 2, '4-2', '3,2;5-2;10')->toArray();
```
> Result: [2,3,4,1,2,4,3,2,3,4,5,4,3,2,10]


### Standard Manipulation Methods

| Method                                                       | Description                                                                                           |
|--------------------------------------------------------------|-------------------------------------------------------------------------------------------------------|
| toArray()                                                    | Get the raw Array.                                                                                    |
| set($key, $value)                                            | Set the value to the key no matter what.                                                              |
| get($key, $default)                                          | Get the value related to the key.                                                                     |
| atIndex(int $index)                                          | Get the item at the given index (numerically).                                                        |
| keyOf($value)                                                | Get the key of a value if exists.                                                                     |
| indexOf($value)                                              | Get the index of a value if exists (numerically).                                                     |
| containsKey($key)                                            | Test is the key exists.                                                                               |
| contains($value)                                             | Test if this values exists.                                                                           |
| add($item)                                                   | Add a new value to the collection, next numeric index will be used. (in-place).                       |
| append(iterable $values)                                     | Append the items at the end of the collection not regarding the keys. (in-place).                     |
| clear()                                                      | Clear the collection of all its items. (in-place).                                                    |
| remove($key)                                                 | Remove the $key/value in the collection. (in-place).                                                  |
| pull($key)                                                   | Remove the $key/value in the collection and return the removed value. (in-place).                     |
| first(callable $callback)                                    | Get the first time and reset and rewind.                                                              |
| shift()                                                      | Shift an element off the beginning of the collection. (in-place).                                     |
| pop()                                                        | Shift an element off the end of the collection. (in-place).                                           |
| last(callable $callback)                                     | Get the last item.                                                                                    |
| map(callable $callback)                                      | Map and return a new Collection.                                                                      |
| mapKeys(callable $callback, callable $callbackValue)         | Map keys (and/or value) and return a new Collection.                                                  |
| transform(callable $callback)                                | Map. (in-place).                                                                                      |
| filter(callable $callback)                                   | Filter and return a new Collection.                                                                   |
| prune(callable $callback)                                    | Filter. (in-place).                                                                                   |
| combine(iterable $values, bool $inPlace)                     | Combine and return a new Collection.                                                                  |
| replace(iterable $values)                                    | Combine. (in-place).                                                                                  |
| combineKeys(iterable $keys)                                  | Opposite of Combine It keeps the values of the current Collection and assign new keys.                |
| reindex(iterable $keys)                                      | CombineKeys. (in-place).                                                                              |
| reduce(callable $callback, $initial)                         | Reduce.                                                                                               |
| each(callable $callback)                                     | Run the callback on each element. (passive).                                                          |
| flip()                                                       | Flip the keys and the values and return a new Collection.                                             |
| invert()                                                     | Flip. (in-place).                                                                                     |
| merge(iterable $items, bool $inPlace)                        | Merge the items and the collections and return a new Collection.                                      |
| coalesce(iterable $items)                                    | Merge. (in-place).                                                                                    |
| union(iterable $items, bool $inPlace)                        | Union the collection with Items and return a new Collection.                                          |
| absorb(iterable $items)                                      | Union. (in-place).                                                                                    |
| assert(callable $callback, bool $expected)                   | Assert that the callback result is $expected for all.                                                 |
| values()                                                     | Return all the values.                                                                                |
| keys()                                                       | Return all the keys.                                                                                  |
| pipe(callable $callback)                                     | Pass the collection to the given callback and return the result.                                      |
| shuffle()                                                    | Random. (in-place).                                                                                   |
| random()                                                     | Shuffle and return a new Collection.                                                                  |
| unique()                                                     | Deduplicate the collection and return a new Collection.                                               |
| implode(string $separator)                                   | Join the items using the $separator.                                                                  |
| distinct()                                                   | Unique. (in-place).                                                                                   |
| zip(iterable $items)                                         | Merge the values items by items and return a new Collection.                                          |
| reverse()                                                    | Reverse the collection and return a new Collection.                                                   |
| inverse()                                                    | Reverse. (in-place).                                                                                  |
| isEmpty()                                                    | Tells if the collection is empty.                                                                     |
| split(int $count)                                            | Split in the collection in $count parts and return a new Collection.                                  |
| chunk(int $size)                                             | Chunk of $size sub collection and return a new Collection.                                            |
| slice(int $offset, int $length)                              | Get a slice of the collection and inject it in a new Collection.                                      |
| keep(int $offset, int $length)                               | Keep a slice of the collection. (in-place).                                                           |
| applyOn($key, callable $onKeyFound, callable $onKeyNotFound) | Apply the callback $onKeyFound on the key/value if the key exists. (passive).                         |
| cut(int $offset, int $length)                                | Cut a slice of the collection. (in-place).                                                            |
| diff(iterable $items)                                        | Compares the collection against $items and returns the values that are not present in the collection. |
| diffKeys(iterable $items)                                    | Compares the collection against $items and returns the keys that are not present in the collection.   |
| intersect(iterable $items)                                   | Compares the collection against $items and returns the values that exist in the collection.           |
| intersectKeys(iterable $items)                               | Compares the collection against $items and returns the keys that exist in the collection.             |
| exists(callable $callback)                                   | Return true if one item return true to the callback.                                                  |

## Contributing

In order to be accepted, your contribution needs to pass a few controls: 

* PHP files should be valid
* PHP files should follow the [PSR-2](http://www.php-fig.org/psr/psr-2/) standard
* PHP files should be [phpmd](https://phpmd.org) and [phpcpd](https://github.com/sebastianbergmann/phpcpd) warning/error free

To ease the validation process, you can use these 2 scripts:

### Coding Standards and syntax

```bash
make codeclean
```
> will check and fix the Coding Standards

### Tests

```bash
make tests
```
> will run the tests

## Changelog

[Changelog](doc/CHANGELOG.md)


## LICENSE

[License](LICENSE)
