<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// replace with file to your own project bootstrap
include __DIR__.'/bootstrap_doctrine.php';

return ConsoleRunner::createHelperSet($entityManager);
