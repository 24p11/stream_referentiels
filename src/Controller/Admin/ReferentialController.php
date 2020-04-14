<?php


namespace App\Controller\Admin;


use App\Form\Admin\Referential\LoadForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReferentialController extends AbstractController
{
    public function index()
    {
        return $this->render('admin/referential/index.html.twig');
    }

    public function load()
    {
        $form = $this->createForm(LoadForm::class);

        return $this->render('admin/referential/load.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}