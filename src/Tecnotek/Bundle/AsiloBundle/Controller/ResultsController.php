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
        return $this->render('TecnotekAsiloBundle:Admin:results/cognitive_activities.html.twig',
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
            $em = $this->getDoctrine()->getManager();
            $item = $em->getRepository("TecnotekAsiloBundle:ActivityItem")->find($itemId);
            $readingData = $em->getRepository("TecnotekAsiloBundle:Catalog\\Dance")->getYesNoData($itemId);
            return $this->render('TecnotekAsiloBundle:Admin:results/yes_no_result.html.twig',
                array(
                    'item'   => $item,
                    'data'   => $readingData,
                ));
        } catch (Exception $e) {
            $info = toString($e);
            $logger->err('ResultsController::renderYesNoResultsAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }
}