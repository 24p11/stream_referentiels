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
     * @QueryParam(name="search", description="Search in referential")
     * @QueryParam(name="start_date")
     * @QueryParam(name="end_date")
     *
     * @param string $type
     * @param string $search
     * @param $start_date
     * @param $end_date
     * @return View
     */
    public function referential($type, $search, $start_date, $end_date): View
    {
        $repositories = $this->getDoctrine()
            ->getRepository(Referential::class)
            ->fullTextSearch($type, $search, $start_date, $end_date);

        return View::create($repositories, Response::HTTP_OK);
    }


}
