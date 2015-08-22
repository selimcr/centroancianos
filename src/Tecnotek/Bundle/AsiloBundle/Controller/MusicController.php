<?php

namespace Tecnotek\Bundle\AsiloBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Tecnotek\Bundle\AsiloBundle\Entity\Catalog\Music;

class MusicController extends Controller
{
    /**
     * @Route("/music", name="_admin_music")
     * @Security("is_granted('ROLE_ADMIN')")
     * @Template()
     */
    public function musicAction() {
        return $this->render('TecnotekAsiloBundle:Admin:music.html.twig');
    }

    /**
     * Return a List of Musics paginated for Bootstrap Table
     *
     * @Route("/music/paginatedList", name="_music_paginated_list")
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
                $paginator = $em->getRepository('TecnotekAsiloBundle:Catalog\Music')
                    ->getPageWithFilter($offset, $limit, $search, $sort, $order);

                $results = array();

                $translator = $this->get('translator');

                foreach($paginator as $music){
                    array_push($results, array('id' => $music->getId(),
                        'name' => $music->getName()));
                }
                return new Response(json_encode(array('total'   => count($paginator),
                    'rows'    => $results)));
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('Music::paginatedListAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * Save or Update a Music
     *
     * @Route("/music/save", name="_music_save")
     * @Template()
     */
    public function saveMusicAction() {
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
                    $music = new Music();
                    if($id != 0) { //It's updating, find the music
                        $music = $em->getRepository('TecnotekAsiloBundle:Catalog\Music')->find($id);
                    }
                    if( isset($music) ) {
                        $music->setName($name);
                        $em->persist($music);
                        $em->flush();
                        return new Response(json_encode(array(
                            'error' => false,
                            'msg' => $translator->trans('music.save.success'))));
                    } else {
                        return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
                    }
                } else {
                    return new Response(json_encode(array('error' => true, 'msg' => "Missing Parameters")));
                }
            } catch (Exception $e) {
                $info = toString($e);
                $logger->err('Music::saveMusicAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'msg' => $info)));
            }
        }// endif this is an ajax request
        else {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    /**
     * Delete a Music
     *
     * @Route("/music/delete", name="_music_delete")
     * @Template()
     */
    public function deleteMusicAction() {
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
                $music = new Music();
                $music = $em->getRepository('TecnotekAsiloBundle:Catalog\Music')->find($id);
                if( isset($music) ) {
                    $em->remove($music);
                    $em->flush();
                    return new Response(json_encode(array(
                        'error' => false,
                        'msg' => $translator->trans('music.delete.success'))));
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
            $logger->err('Music::saveMusicAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'msg' => $info)));
        }
    }
}