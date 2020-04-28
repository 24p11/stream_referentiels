<?php

namespace App\DataFixtures;

use App\Entity\Referential;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReferentialFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENTIAL_REFERENCE = 'referential';

    public function load(ObjectManager $em)
    {
        $referential1 = new Referential();
        $referential1->setType($this->getReference(ReferentialTypeFixtures::REFERENTIAL_TYPE_REFERENCE));
        $referential1->setRefId('AAFF06');
        $referential1->setLabel('Label');
        $referential1->setStartDate(new \DateTime());

        $referential2 = new Referential();
        $referential2->setType($this->getReference(ReferentialTypeFixtures::REFERENTIAL_TYPE_REFERENCE));
        $referential2->setRefId('AAFF07');
        $referential2->setLabel('Label');
        $referential2->setStartDate(new \DateTime());

        $em->persist($referential1);
        $em->persist($referential2);
        $em->flush();

        $this->addReference(self::REFERENTIAL_REFERENCE, $referential1);
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            ReferentialTypeFixtures::class
        ];
    }
}
