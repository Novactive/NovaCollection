<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
include __DIR__.'/bootstrap_doctrine.php';

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;

/** @var EntityManager $entityManager */
$loader = new Loader();
$loader->loadFromDirectory(__DIR__.'/Fixtures');

$purger   = new ORMPurger();
$executor = new ORMExecutor($entityManager, $purger);
$executor->execute($loader->getFixtures());
