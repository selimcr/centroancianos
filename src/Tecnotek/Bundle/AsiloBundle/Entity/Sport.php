<?php

namespace Tecnotek\Bundle\AsiloBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="tecnotek_sports")
 * @ORM\Entity(repositoryClass="Tecnotek\Bundle\AsiloBundle\Repository\SportRepository")
 * @UniqueEntity("name")
 */
class Sport
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Patient", mappedBy="sports")
     */
    private $patients;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
    }

    public function getId(){
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPatients($patients)
    {
        $this->patients = $patients;
    }

    public function getPatients()
    {
        return $this->patients;
    }

    public function __toString(){
        return $this->name;
    }
}
?>
