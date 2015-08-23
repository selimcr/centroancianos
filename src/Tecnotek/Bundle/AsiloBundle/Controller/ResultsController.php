<?php

namespace Tecnotek\Bundle\AsiloBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ResultsController extends Controller
{
    /**
     * @Route("/results/cognitiveActivites", name="_admin_results_cognitive_activities")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function cognitiveActivitiesAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $activityType = $em->getRepository("TecnotekAsiloBundle:ActivityType")->find(1);
        return $this->render('TecnotekAsiloBundle:Admin:results/cognitive_activities.html.twig',
            array(
                'activityType'   => $activityType,
            ));
    }

    /**
     * @Route("/results/aspectosRecreativos", name="_admin_results_aspectos_recreativos")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function aspectosRecreativosAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $activityType = $em->getRepository("TecnotekAsiloBundle:ActivityType")->find(2);
        return $this->render('TecnotekAsiloBundle:Admin:results/aspectos_recreativos.html.twig',
            array(
                'activityType'   => $activityType,
            ));
    }

    /**
     * Return a List of Patients paginated for Bootstrap Table
     *
     * @Route("/activities/{itemId}/results/yes-no", name="_activity_results_yes_no")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function renderYesNoResultsAction($itemId) {
        $logger = $this->get('logger');
        try {
            $useActivityTitle = $this->getRequest()->get('useActivityTitle');
            $em = $this->getDoctrine()->getManager();
            $item = $em->getRepository("TecnotekAsiloBundle:ActivityItem")->find($itemId);
            $title = $useActivityTitle == 0? $item->getTitle():$item->getActivity()->getTitle();
            $readingData = $em->getRepository("TecnotekAsiloBundle:Catalog\\Dance")->getYesNoData($itemId);
            return $this->render('TecnotekAsiloBundle:Admin:results/yes_no_result.html.twig',
                array(
                    'item'   => $item,
                    'data'   => $readingData,
                    'title'  => $title,
                ));
        } catch (Exception $e) {
            $info = toString($e);
            $logger->err('ResultsController::renderYesNoResultsAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }

    /**
     * Return a List of Patients paginated for Bootstrap Table
     *
     * @Route("/activities/{itemId}/results/yes-no-plus-entity", name="_activity_results_yes_no_plus_entity")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function renderYesNoPlusEntityResultsAction($itemId) {
        $logger = $this->get('logger');
        try {
            $em = $this->getDoctrine()->getManager();
            $item = $em->getRepository("TecnotekAsiloBundle:ActivityItem")->find($itemId);
            $entityItem = $em->getRepository("TecnotekAsiloBundle:ActivityItem")->find($itemId + 1);

            $readingData = $em->getRepository("TecnotekAsiloBundle:Catalog\\Dance")->getYesNoData($itemId);
            $entityTableData = $em->getRepository("TecnotekAsiloBundle:Catalog\\Dance")
                ->getEntityActivityData($entityItem);
            return $this->render('TecnotekAsiloBundle:Admin:results/yes_no_plus_entity_result.html.twig',
                array(
                    'item'   => $item,
                    'entityItem'    => $entityItem,
                    'data'   => $readingData,
                    'entityTableData'   => $entityTableData,
                ));
        } catch (Exception $e) {
            $info = toString($e);
            $logger->err('ResultsController::renderYesNoResultsAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }
}