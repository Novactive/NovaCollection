<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Novactive\Tests\Doctrine\Entity;

use Datetime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class OrderItem.
 *
 * @ORM\Table(name="OrderItems")
 * @ORM\Entity
 */
class OrderItem
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Datetime
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var Order
     * @ORM\ManyToOne(targetEntity="Novactive\Tests\Doctrine\Entity\Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;

    /**
     * OrderItem constructor.
     */
    public function __construct()
    {
        $this->created = new DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param Datetime $created
     */
    public function setCreated(Datetime $created)
    {
        $this->created = $created;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param array $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
