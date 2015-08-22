<?php
namespace Tecnotek\Bundle\AsiloBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Religion;

/**
 *
 */
class ReligionRepository extends EntityRepository
{

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    public function getPageWithFilter($offset, $limit, $search, $sort, $order ){
        $dql = "SELECT r FROM TecnotekAsiloBundle:Catalog\Religion r";
        $dql .= ($search == "")? "":" WHERE r.name LIKE :search";
        $dql .= ($sort == "")? "":" order by r." . $sort . " " . $order;

        $query = $this->getEntityManager()->createQuery($dql)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if($search != ""){
            $query->setParameter('search', "%" . $search . "%");
        }

        $paginator = new Paginator($query, $fetchJoinCollection = false);

        return $paginator;
    }
}
?>
