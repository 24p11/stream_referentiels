<?php


namespace App\Form\Admin\Referential;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LoadForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csv', FileType::class, [
                'label' => 'Charger un référentiel (.csv)',
                'attr' => [
                    'accept' => '.csv'
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Charger',
            ]);
    }
}