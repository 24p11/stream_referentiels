<?php


namespace App\Controller\Admin;


use App\Entity\Repositories;
use App\Form\Admin\Referential\LoadReferentialType;
use App\Service\Admin\LoadCsvService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ReferentialController extends AbstractController
{
    public function index()
    {
        return $this->render('admin/referential/index.html.twig');
    }

    public function load(Request $request, LoadCsvService $loadCsvService)
    {
        $form = $this->createForm(LoadReferentialType::class, new Repositories());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $referential_csv = $form->get('csv')->getData();

            if ($referential_csv) {
                $repositories = $loadCsvService->toRepositories($referential_csv);
                $em = $this->getDoctrine()->getManager();
                array_walk($repositories, [$em, 'persist']);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('load'));
        }

        return $this->render('admin/referential/load.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}