<?php
namespace Tecnotek\Bundle\AsiloBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tecnotek\Bundle\AsiloBundle\Entity\Sport;

/**
 *
 */
class ActivityRepository extends EntityRepository
{

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    public function getActivities($activityId){
        $dql = "SELECT a FROM TecnotekAsiloBundle:Activity a where a.activityType = ".$activityId;

        $query = $this->getEntityManager()->createQuery($dql);

        $paginator = new Paginator($query, $fetchJoinCollection = false);

        return $paginator;
    }
}
?>
