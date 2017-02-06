<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Doctrine\Common\Collections;

/**
 * Class AbstractLazyCollection.
 *
 * Override through composer and re implementation using NovaCollection
 */
abstract class AbstractLazyCollection implements Collection
{
    /**
     * The backed collection to use.
     *
     * @var Collection
     */
    protected $collection;

    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * Lazy init for all.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $this->initialize();

        return call_user_func_array([$this->collection, $name], $arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        if (!$this->initialized) {
            $this->doInitialize();
            $this->initialized = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    abstract protected function doInitialize();
}
