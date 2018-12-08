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

namespace Novactive\Collection\Selector;

use Novactive\Collection\Collection;
use Novactive\Collection\Factory;

class Range
{
    /**
     * Explode the selector to get ranges.
     */
    protected function getRanges(string $selector): Collection
    {
        return Factory::create(explode(';', $selector))->map(
            function ($range) {
                $separator = $this->dotDotSeparator($range);
                if (false != $separator) {
                    return Factory::create([(int) substr($range, 0, $separator), (int) substr($range, $separator + 2)]);
                }
                $separator = $this->columnSeparator($range);
                if (false != $separator) {
                    return Factory::create([(int) substr($range, 0, $separator), (int) substr($range, $separator + 1)]);
                }
                $separator = $this->dashSeparator($range);
                if (false != $separator) {
                    return Factory::create([(int) substr($range, 0, $separator), (int) substr($range, $separator + 1)]);
                }
                $separator = $this->commaSeparator($range);
                if (false != $separator) {
                    return Factory::create(
                        [
                            (int) substr($range, 0, $separator),
                            (int) substr($range, 0, $separator) + (int) (substr($range, $separator + 1) - 1),
                        ]
                    );
                }

                // just a number here
                return Factory::create([(int) $range, (int) $range]);
            }
        );
    }

    public function supports(Collection $parameters): bool
    {
        return $parameters->assert(
            function ($param) {
                if (\is_array($param) && 2 == count($param)) {
                    return true;
                }

                return 1 == preg_match('/^([0-9])*([,-:;\\.]*)([0-9])*$/uis', (string) $param);
            },
            true
        );
    }

    public function convert(Collection $parameters, Collection $collection)
    {
        $selector = $parameters->map(
            function ($param) {
                if ((\is_array($param) && 2 == count($param))) {
                    return implode(':', $param);
                }

                return $param;
            }
        )->implode(';');

        $newCollection = Factory::create();

        return $this->getRanges($selector)->reduce(
            function (Collection $accumulator, Collection $range) use ($collection) {
                $from = $range->first();
                $to   = $range->last();
                if ($to >= $from) {
                    return $accumulator->append($collection->slice((int) $from, (int) (($to - $from) + 1)));
                }

                return $accumulator->append($collection->slice((int) $to, (int) (($from - $to) + 1))->inverse());
            },
            $newCollection
        );
    }

    /**
     * @return bool|int
     */
    protected function dotDotSeparator(string $string)
    {
        return strpos($string, '..');
    }

    /**
     * @return bool|int
     */
    protected function columnSeparator(string $string)
    {
        return strpos($string, ':');
    }

    /**
     * @return bool|int
     */
    protected function dashSeparator(string $string)
    {
        return strpos($string, '-');
    }

    /**
     * @return bool|int
     */
    protected function commaSeparator(string $string)
    {
        return strpos($string, ',');
    }
}
