<?php
// src/CupBundle/Entity/Cup.php
namespace CupBundle\Entity;

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
    private $userId;

    /**
     * @ORM\Column(type="integer")
     */
    private $cost;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $isLong;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

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
        return $this->userId;
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
        $this->userId = $userId;

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
     * Get $isLong
     *
     * @return boolean
     */
    public function getIsLong()
    {
        return $this->isLong;
    }

    /**
     * Set cost
     *
     * @param boolean $isLong
     *
     * @return Cup
     */
    public function setIsLong($isLong)
    {
        $this->isLong = $isLong;

        return $this;
    }

    /**
     * Set CreateDate
     *
     * @param string $createDate
     *
     * @return Cup
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

}
