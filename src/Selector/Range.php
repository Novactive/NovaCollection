<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Novactive\Collection\Selector;

use Novactive\Collection\Collection;
use Novactive\Collection\Factory;

/**
 * Class Range.
 */
class Range
{
    /**
     * Explode the selector to get ranges.
     *
     * @param $selector
     *
     * @return Collection
     */
    protected function getRanges($selector)
    {
        return Factory::create(explode(';', $selector))->map(
            function ($range) {
                if ($separator = $this->dotDotSeparator($range) != false) {
                    return Factory::create([substr($range, 0, $separator), substr($range, $separator + 2)]);
                }
                if (($separator = $this->columnSeparator($range) != false) ||
                    ($separator = $this->dashSeparator($range) != false)
                ) {
                    return Factory::create([substr($range, 0, $separator), substr($range, $separator + 1)]);
                }
                if ($separator = $this->commaSeparator($range) != false) {
                    return Factory::create(
                        [
                            substr($range, 0, $separator),
                            intval(substr($range, 0, $separator)) + intval(substr($range, $separator + 1) - 1),
                        ]
                    );
                }

                // just a number here
                return Factory::create([$range, $range]);
            }
        );
    }

    /**
     * @param Collection $parameters
     *
     * @return bool
     */
    public function supports(Collection $parameters)
    {
        return $parameters->assert(
            function ($param) {
                if (is_array($param) && count($param) == 2) {
                    return true;
                }

                return preg_match('/^([0-9])*([,-:;\\.]*)([0-9])*$/uis', $param) == 1;
            },
            true
        );
    }

    /**
     * @param Collection $parameters
     * @param Collection $collectionl
     */
    public function convert(Collection $parameters, Collection $collection)
    {
        $newCollection = Factory::create();
        $selector      = $parameters->map(
            function ($param) {
                if ((is_array($param) && count($param) == 2)) {
                    return implode(':', $param);
                }

                return $param;
            }
        )->implode(';');

        return $this->getRanges($selector)->reduce(
            function (Collection $accumulator, Collection $range) use ($collection) {
                $from = $range->first();
                $to   = $range->last();
                if ($to >= $from) {
                    return $accumulator->append($collection->slice($from, ($to - $from) + 1));
                }

                return $accumulator->append($collection->slice($to, ($from - $to) + 1)->inverse());
            },
            $newCollection
        );
    }

    /**
     * @param $string
     *
     * @return bool|int
     */
    protected function dotDotSeparator($string)
    {
        return strpos($string, '..');
    }

    /**
     * @param $string
     *
     * @return bool|int
     */
    protected function columnSeparator($string)
    {
        return strpos($string, ':');
    }

    /**
     * @param $string
     *
     * @return bool|int
     */
    protected function dashSeparator($string)
    {
        return strpos($string, '-');
    }

    /**
     * @param $string
     *
     * @return bool|int
     */
    protected function commaSeparator($string)
    {
        return strpos($string, ',');
    }
}
