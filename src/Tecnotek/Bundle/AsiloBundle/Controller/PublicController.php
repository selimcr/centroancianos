<?php

namespace Tecnotek\Bundle\AsiloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PublicController extends Controller {
    public function indexAction() {
        return $this->render('TecnotekAsiloBundle:Public:index.html.twig');
    }
}
