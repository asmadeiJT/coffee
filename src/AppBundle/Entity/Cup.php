<?php
// src/AppBundle/Entity/Cup.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="сoffee_consumption")
 */
class Cup
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cost;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    /**
     * Set user id
     *
     * @param integer $userId
     *
     * @return Cup
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get cost
     *
     * @return integer
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set cost
     *
     * @param integer $cost
     *
     * @return Cup
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Set CreateDate
     *
     * @param string $create_date
     *
     * @return Cup
     */
    public function setCreateDate($create_date)
    {
        $this->create_date = $create_date;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

}
