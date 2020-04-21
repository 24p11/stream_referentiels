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
     * @QueryParam(name="search", requirements="\D+", default="", description="Page of the overview.")
     *
     * @param integer $page
     * @param string $search
     * @return View
     */
    public function page($page, $search): View
    {
        $product = $this->getDoctrine()
            ->getRepository(Referential::class)
            ->fullTextSearch($search);

        return View::create($product, Response::HTTP_OK);
    }


}
