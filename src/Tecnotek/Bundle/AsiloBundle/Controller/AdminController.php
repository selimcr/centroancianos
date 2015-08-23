<?php

namespace Tecnotek\Bundle\AsiloBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminController extends Controller
{
    /**
     * @Route("/", name="_admin_home")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction() {
        // Get initial data to render the dashboard
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getEntityManager();
        $counters = $em->getRepository("TecnotekAsiloBundle:Patient")->getPatientsCounters();
        $session->set('totalMen', $counters['patientCounters'][1]);
        $session->set('totalWomen', $counters['patientCounters'][2]);
        return $this->render('TecnotekAsiloBundle:Admin:index.html.twig',
            array(
                'counters'   => $counters
            ));
    }
}