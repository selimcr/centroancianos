<?php
namespace Tecnotek\Bundle\AsiloBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tecnotek\Bundle\AsiloBundle\Entity\Sport;

/**
 *
 */
class PatientRepository extends EntityRepository
{

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    public function getPageWithFilter($offset, $limit, $search, $sort, $order ){
        $dql = "SELECT s FROM TecnotekAsiloBundle:Patient s";
        $dql .= ($search == "")? "":" WHERE s.isDeleted = false AND s.firstName LIKE :search OR s.lastName LIKE :search OR s.secondSurname LIKE :search";
        $dql .= ($sort == "")? "":" order by s." . $sort . " " . $order;

        $query = $this->getEntityManager()->createQuery($dql)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if($search != ""){
            $query->setParameter('search', "%" . $search . "%");
        }

        $paginator = new Paginator($query, $fetchJoinCollection = false);

        return $paginator;
    }

    public function getPatientsCounters() {
        $sql = "select gender, count(*) as 'count' from tecnotek_patients group by gender;";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $counters = array();
        $result = $stmt->fetchAll();
        foreach($result as $row) {
            $counters[$row['gender']] = $row['count'];
        }

        $sql = "select 	sum(case when age between 0 and 69 then 1 end) as '65-69',
		        sum(case when age between 70 and 80 then 1 end) as '70-80',
                sum(case when age between 81 and 90 then 1 end) as '81-90',
                sum(case when age > 90 then 1 end) as 'Mayor 90'
                from (
                    SELECT TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS age
                    FROM tecnotek_patients
                    WHERE gender = 1
                ) t;";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $maleAgesCounters = array();
        $result = $stmt->fetchAll();
        foreach($result as $row) {
            $maleAgesCounters['65-69'] = $row['65-69'];
            $maleAgesCounters['70-80'] = $row['70-80'];
            $maleAgesCounters['81-90'] = $row['81-90'];
            $maleAgesCounters['Mayor 90'] = $row['Mayor 90'];
        }

        $sql = "select 	sum(case when age between 0 and 69 then 1 end) as '65-69',
		        sum(case when age between 70 and 80 then 1 end) as '70-80',
                sum(case when age between 81 and 90 then 1 end) as '81-90',
                sum(case when age > 90 then 1 end) as 'Mayor 90'
                from (
                    SELECT TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS age
                    FROM tecnotek_patients
                    WHERE gender = 2
                ) t;";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $femaleAgesCounters = array();
        $result = $stmt->fetchAll();
        foreach($result as $row) {
            $femaleAgesCounters['65-69'] = $row['65-69'];
            $femaleAgesCounters['70-80'] = $row['70-80'];
            $femaleAgesCounters['81-90'] = $row['81-90'];
            $femaleAgesCounters['Mayor 90'] = $row['Mayor 90'];
        }

        return array(
            'patientCounters'       => $counters,
            'maleAgesCounters'      => $maleAgesCounters,
            'femaleAgesCounters'    => $femaleAgesCounters,
        );
    }
}
?>
