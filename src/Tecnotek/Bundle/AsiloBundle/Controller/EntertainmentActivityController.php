<?php

namespace Tecnotek\Bundle\AsiloBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Tecnotek\Bundle\AsiloBundle\Entity\Catalog\EntertainmentActivity;

class EntertainmentActivityController extends Controller
{
    /**
     * @Route("/entertainmentActivity", name="_admin_entertainmentActivity")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function entertainmentActivityAction() {
        return $this->render('TecnotekAsiloBundle:Admin:entertainmentActivity.html.twig');
    }

    /**
     * Return a List of Entertainment Activities paginated for Bootstrap Table
     *
     * @Route("/entertainmentActivity/paginatedList", name="_entertainmentActivity_paginated_list")
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
                $paginator = $em->getRepository('TecnotekAsiloBundle:Catalog\EntertainmentActivity')
                    ->getPageWithFilter($offset, $limit, $search, $sort, $order);

                $results = array();

                $translator = $this->get('translator');

                foreach($paginator as $entertainmentActivity){
                    array_push($results, array('id' => $entertainmentActivity->getId(),
                        'name' => $entertainmentActivity->getName()));
                }
                return new Response(json_encode(array('total'   => count($paginator),
                    'rows'    => $results)));
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('EntertainmentActivity::paginatedListAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * Save or Update a EntertainmentActivity
     *
     * @Route("/entertainmentActivity/save", name="_entertainmentActivity_save")
     * @Template()
     */
    public function saveEntertainmentActivityAction() {
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
                    $entertainmentActivity = new EntertainmentActivity();
                    if($id != 0) { //It's updating, find the entertainment Activity
                        $entertainmentActivity = $em->getRepository('TecnotekAsiloBundle:Catalog\EntertainmentActivity')->find($id);
                    }
                    if( isset($entertainmentActivity) ) {
                        $entertainmentActivity->setName($name);
                        $em->persist($entertainmentActivity);
                        $em->flush();
                        return new Response(json_encode(array(
                            'error' => false,
                            'msg' => $translator->trans('entertainmentActivity.save.success'))));
                    } else {
                        return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
                    }
                } else {
                    return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
                }
            } catch (Exception $e) {
                $info = toString($e);
                $logger->err('EntertainmentActivity::saveEntertainmentActivityAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'msg' => $info)));
            }
        }// endif this is an ajax request
        else {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * Delete a EntertainmentActivity
     *
     * @Route("/entertainmentActivity/delete", name="_entertainmentActivity_delete")
     * @Template()
     */
    public function deleteEntertainmentActivityAction() {
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
                $entertainmentActivity = new EntertainmentActivity();
                $entertainmentActivity = $em->getRepository('TecnotekAsiloBundle:Catalog\EntertainmentActivity')->find($id);
                if( isset($entertainmentActivity) ) {
                    $em->remove($entertainmentActivity);
                    $em->flush();
                    return new Response(json_encode(array(
                        'error' => false,
                        'msg' => $translator->trans('entertainmentActivity.delete.success'))));
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
            $logger->err('EntertainmentActivity::saveEntertainmentActivityAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'msg' => $info)));
        }
    }
}