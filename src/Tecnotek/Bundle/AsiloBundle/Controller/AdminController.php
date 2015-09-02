<?php

namespace Tecnotek\Bundle\AsiloBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Tecnotek\Bundle\AsiloBundle\Entity\Patient;
use Tecnotek\Bundle\AsiloBundle\Entity\PatientItem;
use Tecnotek\Bundle\AsiloBundle\Entity\Activity;
use Tecnotek\Bundle\AsiloBundle\Entity\ActivityType;


class AdminController extends Controller
{
    /**
     * @Route("/", name="_admin_home")
     * @Security("is_granted('ROLE_EMPLOYEE')")
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
     * @Security("is_granted('ROLE_EMPLOYEE')")
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
     * @Security("is_granted('ROLE_EMPLOYEE')")
     * @Template()
     */
    public function reportListCatalogAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $results = array();
        $resultsPatients = array();
        $activityType = $em->getRepository("TecnotekAsiloBundle:ActivityType")->getActivityTypes();
        foreach($activityType as $activity){
            array_push($results, array('id' => $activity->getId(), 'name' => $activity->getName()));
        }

        return $this->render('TecnotekAsiloBundle:Admin:reports/report_list_catalog.html.twig',
            array(
                'entities' => $results,
                'patients'   => $resultsPatients,));
    }

    /**
     * @Route("/report/listcatalog", name="_admin_load_group_of_activity")
     */
    public function reportLoadActivitiesAction(){
        $logger = $this->get('logger');
        if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {
            try {
                $request = $this->get('request')->request;
                //$activityTypeId = $request->get('activityTypeId');
                $entity = $request->get('entity');
                $translator = $this->get("translator");

                $limit = $request->get('limit');
                $offset = $request->get('offset');
                $order = $request->get('order');
                $search = $request->get('search');
                $sort = $request->get('sort');


                //if( isset($activityTypeId) ) {
                if( isset($entity) ) {
                    $em = $this->getDoctrine()->getEntityManager();

                    //$activitiesType = $em->getRepository("TecnotekAsiloBundle:Activity")->getActivities($activityTypeId);
                    //$entity = $em->getRepository("TecnotekAsiloBundle:Activity")->getActivityEntity($entity);
                    $entity = $em->getRepository('TecnotekAsiloBundle:Catalog\\'.$entity)
                        ->getPageWithFilter($offset, $limit, $search, $sort, $order);
                    $activities = array();


                    //foreach($activitiesType as $activity){
                    foreach($entity as $activity){
                        array_push($activities, array('id' => $activity->getId(), 'name' => $activity->getName()));
                    }


                    return new Response(json_encode(array('error' => false, 'activities' => $activities)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                //$logger->err('Admin::reportLoadActivities [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * @Route("/report/listcatalog2", name="_admin_load_group_of_patients")
     */
    public function reportLoadPatientsAction(){
        $logger = $this->get('logger');
        if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {
            try {
                $request = $this->get('request')->request;
                $entity = $request->get('entity');
                $activityId = $request->get('activityId');

                $translator = $this->get("translator");

                /*$patients = array();
                array_push($patients, array('id' => "1", 'name' => "Juan"));
                array_push($patients, array('id' => "2", 'name' => "Pedro"));
                */
                if( isset($entity) ) {
                    $em = $this->getDoctrine()->getEntityManager();

                    $entity = $em->getRepository("TecnotekAsiloBundle:Catalog\\".$entity)->getPatientsCatalog($entity, $activityId);
                    $patients = array();
/*
                    foreach($entity as $patient){
                        array_push($patients, array('id' => $patient->getId(), 'name' => ($patient->getFirstName().' '.$patient->getLastName())));
                    }
*/
                    foreach($entity as $row) {
                        //$counters[$row['gender']][$row['value']] = $row['counter'];
                        array_push($patients, array('id' => $row['document_id'], 'name' => ($row['first_name'].' '.$row['last_name'])));
                    }

                    return new Response(json_encode(array('error' => false, 'patients' => $patients)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                //$logger->err('Admin::reportLoadActivities [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }
}