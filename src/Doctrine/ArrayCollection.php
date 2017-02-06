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

use Closure;
use Doctrine\Common\Collections as DoctrineCollections;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use Novactive\Collection\Collection as NovaCollection;
use Novactive\Collection\Factory;

/**
 * Class ArrayCollection.
 *
 * Override through composer and re implementation using NovaCollection
 */
class ArrayCollection extends NovaCollection implements DoctrineCollections\Collection, DoctrineCollections\Selectable
{
    /**
     * {@inheritDoc}
     */
    public function matching(DoctrineCollections\Criteria $criteria)
    {
        $expr     = $criteria->getWhereExpression();
        $filtered = $this->items;

        if ($expr) {
            $visitor  = new ClosureExpressionVisitor();
            $filter   = $visitor->dispatch($expr);
            $filtered = array_filter($filtered, $filter);
        }

        if ($orderings = $criteria->getOrderings()) {
            $next = null;
            foreach (array_reverse($orderings) as $field => $ordering) {
                $next = ClosureExpressionVisitor::sortByField(
                    $field,
                    $ordering == DoctrineCollections\Criteria::DESC ? -1 : 1,
                    $next
                );
            }

            uasort($filtered, $next);
        }

        $offset = $criteria->getFirstResult();
        $length = $criteria->getMaxResults();

        if ($offset || $length) {
            $filtered = array_slice($filtered, (int)$offset, $length);
        }

        return $this->createFrom($filtered);
    }

    /**
     * {@inheritDoc}
     */
    protected function createFrom(array $items)
    {
        return Factory::create($items);
    }

    /**
     * {@inheritDoc}
     */
    public function removeElement($item)
    {
        if ($key = $this->keyOf($item)) {
            $this->remove($key);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function indexOf($element)
    {
        return parent::keyOf($element);
    }

    /**
     * {@inheritDoc}
     */
    public function getKeys()
    {
        return parent::keys();
    }

    /**
     * {@inheritDoc}
     */
    public function getValues()
    {
        return parent::values();
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function forAll(Closure $predicate)
    {
        return parent::assert($predicate, true);
    }

    /**
     * {@inheritDoc}
     */
    public function partition(Closure $predicate)
    {
        $matches = $noMatches = [];

        foreach ($this->items as $key => $item) {
            if ($predicate($key, $item)) {
                $matches[$key] = $item;
                continue;
            }
            $noMatches[$key] = $item;
        }

        return [$this->createFrom($matches), $this->createFrom($noMatches)];
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return __CLASS__.'@'.spl_object_hash($this);
    }

    /**
     * {@inheritDoc}
     */
    public function dump()
    {
        dump($this->items);

        return parent::dump();
    }
}
