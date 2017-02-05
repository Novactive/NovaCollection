<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 *
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Doctrine\Common\Collections;

use Doctrine\Common\Collections;
use Novactive\Collection\Collection as NovaCollection;
use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;
use Novactive\Collection\Factory;

class ArrayCollection extends NovaCollection implements Collections\Collection, Collections\Selectable
{
    /**
     * {@inheritDoc}
     */
    public function matching(Collections\Criteria $criteria)
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
                    $ordering == Collections\Criteria::DESC ? -1 : 1,
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
    protected function createFrom(array $elements)
    {
        return Factory::create($elements);
    }

    public function dump()
    {
        dump($this->items);

        return parent::dump();
    }
}
