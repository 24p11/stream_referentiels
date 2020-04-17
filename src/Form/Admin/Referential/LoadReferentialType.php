<?php


namespace App\Form\Admin\Referential;


use App\Entity\Repositories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoadReferentialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csv', FileType::class,
                [
                    'label' => 'Charger un référentiel (.csv)',
                    'mapped' => false,
                    'required' => false,
                    'attr' => [
                        'accept' => '.csv'
                    ],
                ]
            )
            ->add('save', SubmitType::class,
                [
                    'label' => 'Charger',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Repositories::class,
            ]
        );
    }
}