<?php


namespace App\Controller;

use App\Entity\Xx;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractFOSRestController
{

    /**
     * @QueryParam(name="page", requirements="\d+", default="0", description="Page of the overview.")
     *
     * @param integer $page
     * @return View
     */
    public function page($page): View
    {
        $product = $this->getDoctrine()
            ->getRepository(Xx::class)
            ->find($page);

        return View::create($product, Response::HTTP_OK);
    }

}
