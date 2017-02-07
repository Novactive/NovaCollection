| Branch   | Travis | Sensio Insight | Scrutinizer Quality | Scrutinizer Coverage  |
|:--------:|:------:|:--------------:|:-------------------:|:---------------------:|
| develop  | [![Build Status](https://travis-ci.org/Novactive/NovaCollection.svg?branch=develop)](https://travis-ci.org/Novactive/NovaCollection) | [![SensioLabsInsight](https://insight.sensiolabs.com/projects/0d53485d-5fbf-46a0-a1c2-c5c879a78b1e/mini.png)](https://insight.sensiolabs.com/projects/0d53485d-5fbf-46a0-a1c2-c5c879a78b1e) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Novactive/NovaCollection/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Novactive/NovaCollection/?branch=develop) |  [![Code Coverage](https://scrutinizer-ci.com/g/Novactive/NovaCollection/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Novactive/NovaCollection/?branch=develop)
| master   | [![Build Status](https://travis-ci.org/Novactive/NovaCollection.svg?branch=master)](https://travis-ci.org/Novactive/NovaCollection)  | [![SensioLabsInsight](https://insight.sensiolabs.com/projects/8276b281-ea82-423c-8725-b6e9163260de/mini.png)](https://insight.sensiolabs.com/projects/8276b281-ea82-423c-8725-b6e9163260de)|  [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Novactive/NovaCollection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Novactive/NovaCollection/?branch=master) |  [![Code Coverage](https://scrutinizer-ci.com/g/Novactive/NovaCollection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Novactive/NovaCollection/?branch=master)

# Nova Collection

Because we did not find any *Collection* for PHP as simple as we wanted, we did ours.

## Simpler, Better, Faster, Stronger

Using *array_** functions against *foreach* is not an easy pick. As this library is made to manage low level actions.
We wanted it to be fast, then we compared and we implemented the best choices. (have a look: [PHP5.6](http://i.imgur.com/aHfySCH.png), [PHP7.0](http://i.imgur.com/xKoW5nd.png), [PHP7.1](http://i.imgur.com/myHMpSX.png))

Fully unit tested it is reliable.

## Methods

### Manipulation Methods

| Method                                 | Description                                                                                           | Return a new Collection?      |
|----------------------------------------|-------------------------------------------------------------------------------------------------------|-------------------------------|
| map(callable $callback)                | Map and return a new Collection.                                                                      | :negative_squared_cross_mark: |
| transform(callable $callback)          | Map (in-place).                                                                                       | :white_check_mark:            |
| filter(callable $callback)             | Filter and return a new Collection.                                                                   | :negative_squared_cross_mark: |
| prune(callable $callback)              | Filter (in-place).                                                                                    | :white_check_mark:            |
| combine($values,  $inPlace)            | Combine and return a new Collection.                                                                  | :negative_squared_cross_mark: |
| replace($values)                       | Combine (in-place).                                                                                   | :white_check_mark:            |
| combineKeys($keys)                     | Opposite of Combine. It keeps the values of the current Collection and assign new keys.               | :negative_squared_cross_mark: |
| reindex($keys)                         | CombineKeys (in-place).                                                                               | :white_check_mark:            |
| reduce(callable $callback,  $initial)  | Reduce.                                                                                               | :negative_squared_cross_mark: |
| each(callable $callback)               | Run the callback on each element (passive).                                                           | :white_check_mark:            |
| flip()                                 | Flip the keys and the values and return a new Collection.                                             | :negative_squared_cross_mark: |
| invert()                               | Flip (in-place).                                                                                      | :white_check_mark:            |
| merge($items,  $inPlace)               | Merge the items and the collections and return a new Collection.                                      | :negative_squared_cross_mark: |
| coalesce($items)                       | Merge (in-place).                                                                                     | :white_check_mark:            |
| union($items,  $inPlace)               | Union the collection with Items.                                                                      | :negative_squared_cross_mark: |
| absorb($items)                         | Union (in-place).                                                                                     | :white_check_mark:            |
| assert(callable $callback,  $expected) | Assert that the callback result is $expected for all.                                                 | :negative_squared_cross_mark: |
| values()                               | Return all the values.                                                                                | :negative_squared_cross_mark: |
| keys()                                 | Return all the keys.                                                                                  | :negative_squared_cross_mark: |
| pipe(callable $callback)               | Pass the collection to the given callback and return the result.                                      | :negative_squared_cross_mark: |
| shuffle()                              | Shuffle. (random in-place).                                                                           | :white_check_mark:            |
| random()                               | Shuffle and return a new Collection.                                                                  | :negative_squared_cross_mark: |
| unique()                               | Deduplicate the collection and return a new Collection.                                               | :negative_squared_cross_mark: |
| distinct()                             | Unique (in-place).                                                                                    | :white_check_mark:            |
| zip($items)                            | Merge the values items by items.                                                                      | :negative_squared_cross_mark: |
| reverse()                              | Reverse the collection and return a new Collection.                                                   | :negative_squared_cross_mark: |
| inverse()                              | Reverse (in-place).                                                                                   | :white_check_mark:            |
| split($count)                          | Split in the collection in $count parts.                                                              | :negative_squared_cross_mark: |
| chunk($size)                           | Chunk of $size sub collection.                                                                        | :negative_squared_cross_mark: |
| slice($offset,  $length)               | Get a slice of the collection and inject it in a new one.                                             | :negative_squared_cross_mark: |
| keep($offset,  $length)                | Keep a slice of the collection (in-place).                                                            | :white_check_mark:            |
| cut($offset,  $length)                 | Cut a slice of the collection (in-place).                                                             | :white_check_mark:            |
| diff($items)                           | Compares the collection against $items and returns the values that are not present in the collection. | :negative_squared_cross_mark: |
| diffKeys($items)                       | Compares the collection against $items and returns the keys that are not present in the collection.   | :negative_squared_cross_mark: |
| intersect($items)                      | Compares the collection against $items and returns the values that exist in the collection.           | :negative_squared_cross_mark: |
| intersectKeys($items)                  | Compares the collection against $items and returns the keys that exist in the collection.             | :negative_squared_cross_mark: |

### Standard Methods

| Method                                 | Description                                                                            | Return a new Collection?      |
|----------------------------------------|----------------------------------------------------------------------------------------|-------------------------------|
| set($key,  $value)                     | Set the value to the key no matter what.                                               | :white_check_mark:            |
| get($key,  $default)                   | Get the value related to the key.                                                      | :negative_squared_cross_mark: |
| containsKey($key)                      | Test is the key exists.                                                                | :negative_squared_cross_mark: |
| contains($value)                       | Test if this values exists.                                                            | :negative_squared_cross_mark: |
| exits(callable $callback)              | Return true if one item return true to the callback.                                   | :negative_squared_cross_mark: |
| add($item)                             | Add a new value to the collection, next numeric index will be used.                    | :white_check_mark:            |
| remove($key)                           | Remove the $key/value in the collection.                                               | :white_check_mark:            |
| pull($key)                             | Remove the $key/value in the collection and return the removed value.                  | :negative_squared_cross_mark: |
| first(callable $callback)              | Get the first time and reset and rewind.                                               | :negative_squared_cross_mark: |
| shift()                                | Shift an element off the beginning of the collection(in-place).                        | :negative_squared_cross_mark: |
| pop()                                  | Shift an element off the beginning of the collection(in-place).                        | :negative_squared_cross_mark: |
| last(callable $callback)               | Get the last item.                                                                     | :negative_squared_cross_mark: |
| atIndex($index)                        | Get the item at the given index (numerically).                                         | :negative_squared_cross_mark: |
| keyOf($value)                          | Get the key of a value if exists.                                                      | :negative_squared_cross_mark: |
| indexOf($value)                        | Get the index of a value if exists (numerically).                                      | :negative_squared_cross_mark:  |


## Contributing

In order to be accepted, your contribution needs to pass a few controls: 

* PHP files should be valid
* PHP files should follow the [PSR-2](http://www.php-fig.org/psr/psr-2/) standard
* PHP files should be [phpmd](https://phpmd.org) and [phpcpd](https://github.com/sebastianbergmann/phpcpd) warning/error free

To ease the validation process, you can use these 2 scripts:

### Coding Standards and syntax

```bash
bash scripts/codechecker.bash
```
> will check and fix the Coding Standards

### Tests

```bash
bash scripts/runtests.bash
```
> will run the tests

## Changelog

[Changelog](doc/CHANGELOG.md)


## LICENSE

[License](LICENSE)
