<?php
/**
 * Novactive Collection Test Bootstrap.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

putenv('DEBUG_COLLECTION_CLASS=Novactive\Collection\Debug\Collection');
include __DIR__.'/../bootstrap.php';

$paths     = [__DIR__.'/Entity/'];
$isDevMode = true;

// the connection configuration
$dbParams = [
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'localdb',
    'dbname'   => 'localdb',
    'host'     => '127.0.01',
    'port'     => 3307,
];

//$dbParams = [
//    'driver' => 'pdo_sqlite',
//    'path'   => __DIR__.'/db.sqlite',
//];

$config        = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);
