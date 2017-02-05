<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Novactive\Tests\Doctrine\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Novactive\Tests\Doctrine\Entity\Order as OrderEntity;
use Novactive\Tests\Doctrine\Entity\OrderItem as OrderItemEntity;
use Novactive\Tests\Doctrine\Entity\User as UserEntity;

/**
 * Class Order.
 */
class Order extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $order = new OrderEntity();

        for ($i = 1; $i <= 10; $i++) {
            $item = new OrderItemEntity();
            $item->setName("Product {$i}");
            $order->addItem($item);
        }
        $user = $this->getReference('user');
        /* @var UserEntity $user */
        $order->setUser($user);

        $manager->persist($user);
        $manager->persist($order);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
