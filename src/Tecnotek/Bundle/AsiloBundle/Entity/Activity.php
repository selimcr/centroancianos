<?php

namespace Tecnotek\Bundle\AsiloBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="tecnotek_activities")
 * @ORM\Entity(repositoryClass="Tecnotek\Bundle\AsiloBundle\Repository\ActivityRepository")
 * @UniqueEntity("title")
 */
class Activity
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="ActivityType", inversedBy="activities")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $activityType;

    /**
     * @ORM\OneToMany(targetEntity="ActivityItem", mappedBy="activity")
     */
    private $items;

    public function __construct() {
        $this->items = new ArrayCollection();
    }

    public function getId(){
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getActivityType()
    {
        return $this->activityType;
    }

    public function setActivityType($activityType){
        $this->activityType = $activityType;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function __toString(){
        return $this->title;
    }
}
?>
