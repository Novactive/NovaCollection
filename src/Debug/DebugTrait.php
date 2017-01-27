<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Novactive\Collection\Debug;

/**
 * Trait DebugTrait.
 */
trait DebugTrait
{
    /**
     * {@inheritdoc}
     *
     * @see \Novactive\Collection\Collection::doTrhow
     */
    protected function doThrow($message, $arguments)
    {
        ob_start();
        dump($this->items);
        dump($arguments);
        $output = ob_get_contents();
        ob_flush();
        $message .= PHP_EOL.$output;
        parent::doThrow($message, $arguments);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Novactive\Collection\Collection::dump
     */
    public function dump()
    {
        dump($this->items);

        return parent::dump();
    }
}
