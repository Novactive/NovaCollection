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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Order.
 *
 * @ORM\Table(name="Orders")
 * @ORM\Entity
 */
class Order
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
     * @var
     * @ORM\OneToOne(targetEntity="Novactive\Tests\Doctrine\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Novactive\Tests\Doctrine\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var array
     * @ORM\OneToMany(targetEntity="Novactive\Tests\Doctrine\Entity\OrderItem", mappedBy="order", cascade={"persist"})
     */
    protected $items;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->created = new DateTime();
        $this->items   = new ArrayCollection();
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param array $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    public function addItem(OrderItem $item)
    {
        if (!$this->items->contains($item)) {
            $item->setOrder($this);
            $this->items->add($item);
        }
    }
}
