<?php

namespace Tecnotek\Bundle\AsiloBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Tecnotek\Bundle\AsiloBundle\Entity\Catalog\SpiritualActivity;

class SpiritualActivityController extends Controller
{
    /**
     * @Route("/spiritualActivity", name="_admin_spiritualActivity")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function spiritualActivityAction() {
        return $this->render('TecnotekAsiloBundle:Admin:spiritualActivity.html.twig');
    }

    /**
     * Return a List of Spiritual Activities paginated for Bootstrap Table
     *
     * @Route("/spiritualActivity/paginatedList", name="_spiritualActivity_paginated_list")
     * @Template()
     */
    public function paginatedListAction() {
        $logger = $this->get('logger');
        if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {
            try {
                //Get parameters
                $request = $this->get('request');
                $limit = $request->get('limit');
                $offset = $request->get('offset');
                $order = $request->get('order');
                $search = $request->get('search');
                $sort = $request->get('sort');

                $em = $this->getDoctrine()->getManager();
                $paginator = $em->getRepository('TecnotekAsiloBundle:Catalog\SpiritualActivity')
                    ->getPageWithFilter($offset, $limit, $search, $sort, $order);

                $results = array();

                $translator = $this->get('translator');

                foreach($paginator as $spiritualActivity){
                    array_push($results, array('id' => $spiritualActivity->getId(),
                        'name' => $spiritualActivity->getName()));
                }
                return new Response(json_encode(array('total'   => count($paginator),
                    'rows'    => $results)));
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('SpiritualActivity::paginatedListAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * Save or Update a SpiritualActivity
     *
     * @Route("/spiritualActivity/save", name="_spiritualActivity_save")
     * @Template()
     */
    public function saveSpiritualActivityAction() {
        $logger = $this->get('logger');
        if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {
            try {
                //Get parameters
                $request = $this->get('request');
                $id = $request->get('id');
                $name = $request->get('name');
                $translator = $this->get("translator");

                if( isset($id) && isset($name) && trim($name) != ""){
                    $em = $this->getDoctrine()->getManager();
                    $spiritualActivity = new SpiritualActivity();
                    if($id != 0) { //It's updating, find the spiritualActivity
                        $spiritualActivity = $em->getRepository('TecnotekAsiloBundle:Catalog\SpiritualActivity')->find($id);
                    }
                    if( isset($spiritualActivity) ) {
                        $spiritualActivity->setName($name);
                        $em->persist($spiritualActivity);
                        $em->flush();
                        return new Response(json_encode(array(
                            'error' => false,
                            'msg' => $translator->trans('spiritualActivity.save.success'))));
                    } else {
                        return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
                    }
                } else {
                    return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
                }
            } catch (Exception $e) {
                $info = toString($e);
                $logger->err('SpiritualActivity::saveSpiritualActivityAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'msg' => $info)));
            }
        }// endif this is an ajax request
        else {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * Delete a SpiritualActivity
     *
     * @Route("/spiritualActivity/delete", name="_spiritualActivity_delete")
     * @Template()
     */
    public function deleteSpiritualActivityAction() {
        $logger = $this->get('logger');

        if (!$this->get('request')->isXmlHttpRequest()) { // Is the request an ajax one?
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }

        try {
            //Get parameters
            $request = $this->get('request');
            $id = $request->get('id');
            $translator = $this->get("translator");

            if( isset($id) ){
                $em = $this->getDoctrine()->getManager();
                $spiritualActivity = new SpiritualActivity();
                $spiritualActivity = $em->getRepository('TecnotekAsiloBundle:Catalog\SpiritualActivity')->find($id);
                if( isset($spiritualActivity) ) {
                    $em->remove($spiritualActivity);
                    $em->flush();
                    return new Response(json_encode(array(
                        'error' => false,
                        'msg' => $translator->trans('spiritualActivity.delete.success'))));
                } else {
                    return new Response(json_encode(array(
                        'error' => true,
                        'msg' => $translator->trans('validation.not.found'))));
                }
            } else {
                return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
            }
        } catch (Exception $e) {
            $info = toString($e);
            $logger->err('SpiritualActivity::saveSpiritualActivityAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'msg' => $info)));
        }
    }
}