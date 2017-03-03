<?php

namespace WebPortalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends Controller {

    public function indexAction() {
        return $this->render('WebPortalBundle:Home:index.html.twig', array('mutant_navbar' => true));
    }
    
    public function tandcAction() {
        return $this->render('WebPortalBundle:Home:tandc.html.twig', array('mutant_navbar' => false));
    }

    public function contactAction(Request $request) {
        
    }

}
