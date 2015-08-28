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
     * @Route("/report/list", name="_admin_patiens_report")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function reportListAction(){
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("TecnotekAsiloBundle:Patient")->getPatients();

        $results = array();

        $request = $this->get('request')->request;
        $gender = $request->get('gender');
        $address = $request->get('address');
        $birthday = $request->get('birthday');

        $translator = $this->get('translator');

        foreach($entity as $patient){

            if($patient->getBirthdate()!= null){
                $birthdaypatient= date_format($patient->getBirthdate(), 'Y-m-d');
            } else $birthdaypatient="";

            if($patient->getGender()==1){
                $genderpatient = 'Masc';
            } else $genderpatient = 'Fem';


            array_push($results, array('id' => $patient->getDocumentId(),
                'name' => ($patient->getFirstName().' '.$patient->getLastName()),
                'gender' => $genderpatient, 'address' => $patient->getAddress(), 'birthday' => $birthdaypatient));
        }

        return $this->render('TecnotekAsiloBundle:Admin:reports/report_list.html.twig', array('entities' => $results, 'gender' => $gender, 'address' => $address, 'birthday' => $birthday));
    }

    /**
     * @Route("/report/listcatalog", name="_admin_patiens_catalog_report")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function reportListCatalogAction(){
        $em = $this->getDoctrine()->getEntityManager();

        //$entity = $em->getRepository("TecnotekAsiloBundle:Patient")->getPatients();

        $results = array();
        $resultsPatients = array();

        $activityType = $em->getRepository("TecnotekAsiloBundle:ActivityType")->getActivityTypes();

        $translator = $this->get('translator');

        foreach($activityType as $activity){

            array_push($results, array('id' => $activity->getId(), 'name' => $activity->getName()));
        }

        return $this->render('TecnotekAsiloBundle:Admin:reports/report_list_catalog.html.twig', array('entities' => $results, 'patients'   => $resultsPatients));
    }
}