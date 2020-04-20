<?php


namespace App\Controller\Api;

use App\Entity\Referential;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReferentialController extends AbstractFOSRestController
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
            ->getRepository(Referential::class)
            ->find($page);

        return View::create($product, Response::HTTP_OK);
    }


}
