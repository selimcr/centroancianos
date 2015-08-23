<?php
namespace Tecnotek\Bundle\AsiloBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Tecnotek\Bundle\AsiloBundle\Entity\Catalog;

/**
 *
 */
class CatalogRepository extends EntityRepository
{

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

    public function getPageWithFilter($offset, $limit, $search, $sort, $order ){
        $dql = "SELECT d FROM " . $this->getEntityName() . " d";
        $dql .= ($search == "")? "":" WHERE d.name LIKE :search";
        $dql .= ($sort == "")? "":" order by d." . $sort . " " . $order;

        $query = $this->getEntityManager()->createQuery($dql)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if($search != ""){
            $query->setParameter('search', "%" . $search . "%");
        }

        $paginator = new Paginator($query, $fetchJoinCollection = false);

        return $paginator;
    }

    public function getYesNoData($itemId, $totalMen, $totalWomen) {
        $sql = "select p.gender, pi.value, count(*) as 'counter'
                from tecnotek_patient_items pi
                left join tecnotek_patients p on pi.patient_id = p.id
                where pi.item_id = " . $itemId . "
                group by p.gender, value;";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();

        $maleCounters = array();
        $maleCounters["0"] = 0;
        $maleCounters["1"] = 0;
        $femaleCounters = array();
        $femaleCounters["0"] = 0;
        $femaleCounters["1"] = 0;
        $counters = array();
        $counters["1"] = $maleCounters;
        $counters["2"] = $femaleCounters;
        $result = $stmt->fetchAll();
        foreach($result as $row) {
            $counters[$row['gender']][$row['value']] = $row['counter'];
        }

        $menYes = $counters["1"]["1"];
        $menNo = $totalMen - $menYes;
        $womenYes = $counters["2"]["1"];
        $womenNo = $totalWomen - $womenYes;
        $menTotal = $menNo + $menYes;
        $womenTotal = $womenNo + $womenYes;

        return array(
            'menYes'        => $menYes,
            'menYesPerc'    => $menTotal == 0? 0:$menYes * 100 / $menTotal,
            'menNo'         => $menNo,
            'menNoPerc'     => $menTotal == 0? 0:$menNo * 100 / $menTotal,
            'menTotal'      => $menTotal,
            'womenYes'        => $womenYes,
            'womenYesPerc'    => $womenTotal == 0? 0:$womenYes * 100 / $womenTotal,
            'womenNo'         => $womenNo,
            'womenNoPerc'     => $womenTotal == 0? 0:$womenNo * 100 / $womenTotal,
            'womenTotal'      => $womenTotal,
            'allNo'         => $menNo + $womenNo,
            'allYes'        => $menYes + $womenYes,
            'allTotal'      => $menNo + $womenNo + $menYes + $womenYes,
            'allNoPerc'     => ($menNo + $womenNo) == 0? 0:($menNo + $womenNo) * 100 / ($menNo + $womenNo + $menYes + $womenYes),
            'allYesPerc'     => ($menYes + $womenYes) == 0? 0:($menYes + $womenYes) * 100 / ($menNo + $womenNo + $menYes + $womenYes),
        );
    }

    public function getEntityActivityData($item) {
        $sql = "select s.*,
        (select count(pi.id) from tecnotek_patient_items pi where pi.item_id = " .
            $item->getId() . " and INSTR(pi.value , '['+s.id+']')) as 'counter'
        from " . $this->getTableNameForEntityItem($item->getReferencedEntity()) . " s
        order by s.name;";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    private function getTableNameForEntityItem($referencedEntity) {
        switch($referencedEntity) {
            case 'Sport': return "tecnotek_sports";
            case 'Reading': return "tecnotek_readings";
            case 'Writing': return "tecnotek_writings";
            case 'Manuality': return "tecnotek_manualities";
            case 'Music': return "tecnotek_musics";
            case 'Instrument': return "tecnotek_instruments";
            case 'EntertainmentActivity': return 'tecnotek_entertainment_activities';
            case "RoomGame": return "tecnotek_room_games";
            case "Dance": return "tecnotek_dances";
            default: return "";
        }
    }
}
?>
