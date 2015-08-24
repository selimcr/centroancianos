<?php

namespace Tecnotek\Bundle\AsiloBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Tecnotek\Bundle\AsiloBundle\Entity\Patient;
use Tecnotek\Bundle\AsiloBundle\Entity\PatientItem;

class AdminController extends Controller
{
    /**
     * @Route("/reports/reportListCatalog/", name="_admin_home")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction() {
        // Get initial data to render the dashboard
        $em = $this->getDoctrine()->getEntityManager();
        $counters = $em->getRepository("TecnotekAsiloBundle:Patient")->getPatientsCounters();
        return $this->render('TecnotekAsiloBundle:Admin:index.html.twig',
            array(
                'counters'   => $counters
            ));
    }

    /**
     * @Route("/", name="_admin_patiens_report")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function reportListCatalogAction(){
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("TecnotekAsiloBundle:Patient")->getPatients();

        $results = array();

        $translator = $this->get('translator');

        foreach($entity as $patient){
            array_push($results, array('id' => $patient->getDocumentId(),
                'name' => ($patient->getFirstName().' '.$patient->getLastName())));
        }

        return $this->render('TecnotekAsiloBundle:Admin:Reports/report_list_catalog.html.twig', array('entities' => $results));
    }
}