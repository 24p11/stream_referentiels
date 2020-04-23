<?php

namespace App\DataFixtures;

use App\Entity\ReferentialType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReferentialTypeFixtures extends Fixture
{
    public const REFERENTIAL_TYPE_REFERENCE = 'referential-type';

    public function load(ObjectManager $em)
    {
        $referentialType = new ReferentialType();
        $referentialType->setId('CIM10');
        $referentialType->setDescription('Description');

        $em->persist($referentialType);
        $em->flush();

        $this->addReference(self::REFERENTIAL_TYPE_REFERENCE, $referentialType);
    }
}
