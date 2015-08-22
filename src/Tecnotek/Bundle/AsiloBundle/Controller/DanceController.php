<?php

namespace Tecnotek\Bundle\AsiloBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Dance;

class DanceController extends Controller
{
    /**
     * @Route("/dance", name="_admin_dance")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function danceAction() {
        return $this->render('TecnotekAsiloBundle:Admin:dance.html.twig');
    }

    /**
     * Return a List of Dances paginated for Bootstrap Table
     *
     * @Route("/dance/paginatedList", name="_dance_paginated_list")
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
                $paginator = $em->getRepository('TecnotekAsiloBundle:Catalog\Dance')
                    ->getPageWithFilter($offset, $limit, $search, $sort, $order);

                $results = array();

                $translator = $this->get('translator');

                foreach($paginator as $dance){
                    array_push($results, array('id' => $dance->getId(),
                        'name' => $dance->getName()));
                }
                return new Response(json_encode(array('total'   => count($paginator),
                    'rows'    => $results)));
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('Dance::paginatedListAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * Save or Update a Dance
     *
     * @Route("/dance/save", name="_dance_save")
     * @Template()
     */
    public function saveDanceAction() {
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
                    $dance = new Dance();
                    if($id != 0) { //It's updating, find the dance
                        $dance = $em->getRepository('TecnotekAsiloBundle:Catalog\Dance')->find($id);
                    }
                    if( isset($dance) ) {
                        $dance->setName($name);
                        $em->persist($dance);
                        $em->flush();
                        return new Response(json_encode(array(
                            'error' => false,
                            'msg' => $translator->trans('dance.save.success'))));
                    } else {
                        return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
                    }
                } else {
                    return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
                }
            } catch (Exception $e) {
                $info = toString($e);
                $logger->err('Dance::saveDanceAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'msg' => $info)));
            }
        }// endif this is an ajax request
        else {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * Delete a Dance
     *
     * @Route("/dance/delete", name="_dance_delete")
     * @Template()
     */
    public function deleteDanceAction() {
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
                $dance = new Dance();
                $dance = $em->getRepository('TecnotekAsiloBundle:Catalog\Dance')->find($id);
                if( isset($dance) ) {
                    $em->remove($dance);
                    $em->flush();
                    return new Response(json_encode(array(
                        'error' => false,
                        'msg' => $translator->trans('dance.delete.success'))));
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
            $logger->err('Dance::saveDanceAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'msg' => $info)));
        }
    }
}