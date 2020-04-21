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
     * @QueryParam(name="referential", description="Referential", allowBlank=false)
     * @QueryParam(name="search", description="Search in referential")
     *
     * @param string $referential
     * @param string $search
     * @return View
     */
    public function page($referential, $search): View
    {
        $product = $this->getDoctrine()
            ->getRepository(Referential::class)
            ->fullTextSearch($referential, $search);

        return View::create($product, Response::HTTP_OK);
    }


}
