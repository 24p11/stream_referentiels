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
     * @QueryParam(name="type", description="Referential type (CIM10...)", allowBlank=false)
     * @QueryParam(name="search", description="Search in referential")
     *
     * @param string $type
     * @param string $search
     * @return View
     */
    public function page($type, $search): View
    {
        $repositories = $this->getDoctrine()
            ->getRepository(Referential::class)
            ->fullTextSearch($type, $search);

        return View::create($repositories, Response::HTTP_OK);
    }


}
