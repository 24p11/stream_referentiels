<?php


namespace App\Controller\Admin;


use App\Entity\Referential;
use App\Entity\ReferentialType;
use App\Form\Admin\Referential\AddType;
use App\Form\Admin\Referential\LoadType;
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
        $referentialType = $em->getRepository(ReferentialType::class)->findOneBy(['id' => $referential]);
        $form = $this->createForm(AddType::class, $referentialType);
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
            $referentialCsv = $form->get('csv')->getData();
            if ($referentialCsv) {
                $em = $this->getDoctrine()->getManager();
                $referentialTypes = $em->getRepository(ReferentialType::class)->findOneBy(['id' => $referential]);
                $repositories = $loadCsvService->toRepositories($referentialCsv, $referentialTypes);
                // Detect create & update & delete

                $repositories = $this->filterExistingReferential($referential, $repositories);
                array_walk($repositories, [$em, 'persist']);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('admin_referential_details', ['referential' => $referential]));
    }

    private function filterExistingReferential(string $referential, array $repositories): array
    {
        return array_reduce($repositories, function (array $accumulator, Referential $referential) {
            // Get existing referential
            $existingReferential = $this->getDoctrine()->getRepository(Referential::class)
                ->findOneBy([
                    'type' => $referential->getType(),
                    'refId' => $referential->getRefId(),
                    'labelHash' => $referential->getLabelHash()
                ]);

            $isOld = $existingReferential && $existingReferential->getUniqueId() === $referential->getUniqueId();
            if (!$isOld) {
                $accumulator[] = $referential;
            }

            return $accumulator;
        }, []);
    }

    public function details(Request $request, string $referential)
    {
        return $this->render('admin/referential/details.html.twig');
    }
}
