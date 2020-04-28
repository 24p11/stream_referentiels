<?php


namespace App\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;

class HomeController extends AbstractFOSRestController
{
    public function index()
    {
        return $this->render('index.html.twig');
    }
}
