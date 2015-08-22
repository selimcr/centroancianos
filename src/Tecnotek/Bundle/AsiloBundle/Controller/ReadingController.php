<?php

namespace Tecnotek\Bundle\AsiloBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Reading;

class ReadingController extends Controller
{
    /**
     * @Route("/reading", name="_admin_reading")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function readingAction() {
        return $this->render('TecnotekAsiloBundle:Admin:reading.html.twig');
    }

    /**
     * Return a List of Readings paginated for Bootstrap Table
     *
     * @Route("/reading/paginatedList", name="_reading_paginated_list")
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
                $paginator = $em->getRepository('TecnotekAsiloBundle:Catalog\Reading')
                    ->getPageWithFilter($offset, $limit, $search, $sort, $order);

                $results = array();

                $translator = $this->get('translator');

                foreach($paginator as $reading){
                    array_push($results, array('id' => $reading->getId(),
                        'name' => $reading->getName()));
                }
                return new Response(json_encode(array('total'   => count($paginator),
                    'rows'    => $results)));
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('Reading::paginatedListAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * Save or Update a Reading
     *
     * @Route("/reading/save", name="_reading_save")
     * @Template()
     */
    public function saveReadingAction() {
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
                    $reading = new Reading();
                    if($id != 0) { //It's updating, find the reading
                        $reading = $em->getRepository('TecnotekAsiloBundle:Catalog\Reading')->find($id);
                    }
                    if( isset($reading) ) {
                        $reading->setName($name);
                        $em->persist($reading);
                        $em->flush();
                        return new Response(json_encode(array(
                            'error' => false,
                            'msg' => $translator->trans('reading.save.success'))));
                    } else {
                        return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
                    }
                } else {
                    return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
                }
            } catch (Exception $e) {
                $info = toString($e);
                $logger->err('Reading::saveReadingAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'msg' => $info)));
            }
        }// endif this is an ajax request
        else {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * Delete a Reading
     *
     * @Route("/reading/delete", name="_reading_delete")
     * @Template()
     */
    public function deleteReadingAction() {
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
                $reading = new Reading();
                $reading = $em->getRepository('TecnotekAsiloBundle:Catalog\Reading')->find($id);
                if( isset($reading) ) {
                    $em->remove($reading);
                    $em->flush();
                    return new Response(json_encode(array(
                        'error' => false,
                        'msg' => $translator->trans('reading.delete.success'))));
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
            $logger->err('Reading::saveReadingAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'msg' => $info)));
        }
    }
}