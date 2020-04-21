<?php


namespace App\Controller\Admin;


use App\Entity\Referential;
use App\Entity\ReferentialType;
use App\Form\Admin\Referential\AddType;
use App\Form\Admin\Referential\LoadType;
use App\Form\Admin\Referential\QyeryType;
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
        $repositories = $em->getRepository(ReferentialType::class)->findAll();

        return $this->render('admin/referential/list.html.twig', [
            'repositories' => $repositories,
        ]);
    }

    public function add(Request $request)
    {
        $form = $this->createForm(AddType::class, new ReferentialType());
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
        $referential_type = $em->getRepository(ReferentialType::class)->findOneBy(['id' => $referential]);
        $form = $this->createForm(AddType::class, $referential_type);
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

    public function manage()
    {
        $form = $this->createForm(LoadType::class, new Referential());

        return $this->render('admin/referential/manage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function load(Request $request, LoadCsvService $loadCsvService, string $referential)
    {
        $form = $this->createForm(LoadType::class, new Referential());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $referential_csv = $form->get('csv')->getData();

            if ($referential_csv) {
                $em = $this->getDoctrine()->getManager();
                $referential_types = $em->getRepository(ReferentialType::class)->findOneBy(['id' => $referential]);
                $repositories = $loadCsvService->toRepositories($referential_csv, $referential_types);
                $repositories = $this->filterExistingReferential($referential, $repositories);
                array_walk($repositories, [$em, 'persist']);
                $em->flush();
            }


        }

        return $this->redirect($this->generateUrl('admin_referential_details', ['referential' => $referential]));
    }

    private function filterExistingReferential(string $referential, array $repositories): array
    {
        $new_referential_ids = array_map(function (Referential $referential) {
            return $referential->getRefId();
        }, $repositories);
        $old_referential_ids = $this->getDoctrine()->getRepository(Referential::class)
            ->whereIn($referential, $new_referential_ids)
            ->getQuery()
            ->getResult();
        $insert_referential_ids = array_diff($new_referential_ids, array_keys($old_referential_ids));

        return array_filter($repositories, function (Referential $referential) use ($insert_referential_ids) {
            return isset(array_flip($insert_referential_ids)[$referential->getRefId()]);
        });
    }

    public function details(Request $request, string $referential)
    {
        return $this->render('admin/referential/details.html.twig', [
            'search_api' => $this->generateUrl('referential', ['version' => $this->getParameter('referential.api_version')])
        ]);
    }
}