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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Novactive\Collection\Collection;
use Novactive\Tests\Doctrine\Entity\Order;
use Novactive\Tests\Doctrine\Entity\OrderItem;

/** @var EntityManager $entityManager */
$repo = $entityManager->getRepository('Novactive\Tests\Doctrine\Entity\Order');

$orders = $repo->findAll();
foreach ($orders as $order) {
    /** @var Order $order */
    $items = $order->getItems();
    dump($items);

    $items->transform(function(OrderItem $value){
        return $value->getName() . " OH YEAH";
    });
    foreach ($items as $item) {
        /* @var OrderItem $item */
        echo $item->getName().PHP_EOL;
    }
}




$override = new ArrayCollection();
$override->add("plop");
$override->dump();



echo PHP_EOL.'------'.PHP_EOL;
