# Nova Collection

Because we did not find any *Collection* for PHP as simple as we wanted, we did ours.

## Simpler, Better, Faster, Stronger

Using *array_** functions against *foreach* is not an easy pick. As this library is made to manage low level actions.
We wanted it to be fast, then we compared and we implemented the best choices. (have a look: [PHP5.6](http://i.imgur.com/cmi3K68.png), [PHP7.0](http://i.imgur.com/CSsZSgm.png), [PHP7.1](http://i.imgur.com/hmvg4EZ.png))

Fully unit tested it is reliable.

## Methods

### Manipulation Methods

| Method                                 | Description                                                                            | In-Place                      |
|----------------------------------------|----------------------------------------------------------------------------------------|-------------------------------|
| map(callable $callback)                | Map and return a new Collection.                                                       | :negative_squared_cross_mark: |
| transform(callable $callback)          | Map (in-place).                                                                        | :white_check_mark:            |
| filter(callable $callback)             | Filter and return a new Collection.                                                    | :negative_squared_cross_mark: |
| prune(callable $callback)              | Filter (in-place).                                                                     | :white_check_mark:            |
| combine($values,  $inPlace)            | Combine and return a new Collection.                                                   | :negative_squared_cross_mark: |
| replace($values)                       | Combine (in-place).                                                                    | :white_check_mark:            |
| combineKeys($keys)                     | Opposite of Combine It keeps the values of the current Collection and assign new keys. | :negative_squared_cross_mark: |
| reindex($keys)                         | CombineKeys (in-place).                                                                | :white_check_mark:            |
| reduce(callable $callback,  $initial)  | Reduce.                                                                                | :negative_squared_cross_mark: |
| each(callable $callback)               | Run the callback on each element (passive).                                            | :negative_squared_cross_mark: |
| flip()                                 | Flip the keys and the values and return a new Collection.                              | :negative_squared_cross_mark: |
| invert()                               | Flip (in-place).                                                                       | :white_check_mark:            |
| merge($items,  $inPlace)               | Merge the items and the collections and return a new Collection.                       | :negative_squared_cross_mark: |
| coalesce($items)                       | Merge (in-place).                                                                      | :white_check_mark:            |
| union($items,  $inPlace)               | Union the collection with Items.                                                       | :negative_squared_cross_mark: |
| absorb($items)                         | Union (in-place).                                                                      | :white_check_mark:            |
| assert(callable $callback,  $expected) | Assert that the callback result is $expected for all.                                  | :negative_squared_cross_mark: |
| values()                               | Return all the values.                                                                 | :negative_squared_cross_mark: |
| keys()                                 | Return all the keys.                                                                   | :negative_squared_cross_mark: |
| pipe(callable $callback)               | Pass the collection to the given callback and return the result.                       | :negative_squared_cross_mark: |
| shuffle()                              | Shuffle. (random in-place).                                                            | :white_check_mark:            |
| random()                               | Shuffle and return a new Collection.                                                   | :negative_squared_cross_mark: |
| unique()                               | Deduplicate the collection and return a new Collection.                                | :negative_squared_cross_mark: |
| distinct()                             | Unique (in-place).                                                                     | :white_check_mark:            |
| reverse()                              | Reverse the collection and return a new Collection.                                    | :negative_squared_cross_mark: |
| inverse()                              | Reverse (in-place).                                                                    | :white_check_mark:            |

### Standard Methods

| Method                                 | Description                                                                            | In-Place                      |
|----------------------------------------|----------------------------------------------------------------------------------------|-------------------------------|
| __construct(array $items)              | Collection constructor.                                                                | :negative_squared_cross_mark: |
| set($key,  $value)                     | Set the value to the key no matter what.                                               | :white_check_mark:            |
| get($key)                              | Get the value related to the key.                                                      | :negative_squared_cross_mark: |
| containsKey($key)                      | Test is the key exists.                                                                | :negative_squared_cross_mark: |
| contains($value)                       | Test if this values exists.                                                            | :negative_squared_cross_mark: |
| add($item)                             | Add a new value to the collection, next numeric index will be used.                    | :white_check_mark:            |
| remove($key)                           | Remove the $key/value in the collection.                                               | :white_check_mark:            |
| pull($key)                             | Remove the $key/value in the collection and return the removed value.                  | :negative_squared_cross_mark: |
| first(callable $callback)              | Get the first time and reset and rewind.                                               |:negative_squared_cross_mark:  |
| last(callable $callback)               | Get the last item.                                                                     |:negative_squared_cross_mark:  |

## Changelog

[Changelog](doc/CHANGELOG.md)


## LICENSE

[License](LICENSE)
