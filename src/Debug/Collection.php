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

namespace Novactive\Collection\Debug;

use Novactive\Collection\Collection as MainCollection;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class Collection for debug.
 *
 * You can create yours and do what you want
 * DebugTrait is just a default example
 */
class Collection extends MainCollection
{
    public function dump(): MainCollection
    {
        if (!getenv('UNIT_TESTS')) {
            VarDumper::dump($this);
        }

        return $this;
    }
}
