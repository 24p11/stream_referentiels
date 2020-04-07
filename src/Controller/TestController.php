<?php


namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractFOSRestController
{

    public function page(): View
    {
        return View::create(["..."], Response::HTTP_OK);
    }

}
