<?php
// src/UserBundle/Entity/User.php
namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", options={"default" : "owner"})
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $amortization = 0;

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
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set amortization
     *
     * @param boolean $amortization
     *
     * @return User
     */
    public function setAmortization($amortization)
    {
        $this->amortization = $amortization;

        return $this;
    }

    /**
     * Get amortization
     *
     * @return boolean
     */
    public function getAmortization()
    {
        return $this->amortization;
    }
}
