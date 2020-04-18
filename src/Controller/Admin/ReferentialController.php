<?php


namespace App\Controller\Admin;


use App\Entity\ReferentialTypes;
use App\Entity\Repositories;
use App\Form\Admin\Referential\AddReferentialType;
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

    public function list()
    {
        $em = $this->getDoctrine()->getManager();
        $repositories = $em->getRepository(ReferentialTypes::class)->findAll();

        return $this->render('admin/referential/list.html.twig', [
            'repositories' => $repositories,
        ]);
    }

    public function add(Request $request)
    {
        $form = $this->createForm(AddReferentialType::class, new ReferentialTypes());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirect($this->generateUrl('admin_referential_list'));
        }

        return $this->render('admin/referential/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(Request $request, string $referential)
    {
        $em = $this->getDoctrine()->getManager();
        $referential_type = $em->getRepository(ReferentialTypes::class)->findOneBy(['type' => $referential]);
        $form = $this->createForm(AddReferentialType::class, $referential_type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirect($this->generateUrl('admin_referential_list'));
        }

        return $this->render('admin/referential/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function load(Request $request, LoadCsvService $loadCsvService, string $referential)
    {
        $form = $this->createForm(LoadReferentialType::class, new Repositories());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $referential_csv = $form->get('csv')->getData();

            if ($referential_csv) {
                $repositories = $loadCsvService->toRepositories($referential_csv, $referential);
                $em = $this->getDoctrine()->getManager();
                array_walk($repositories, [$em, 'persist']);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('admin_referential_load', ['referential' => $referential]));
        }

        return $this->render('admin/referential/load.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}