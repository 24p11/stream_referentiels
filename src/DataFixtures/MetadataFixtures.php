<?php

namespace App\DataFixtures;

use App\Entity\Metadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MetadataFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $em)
    {
        $end_date = new \DateTime();
        $end_date->add(new \DateInterval('P1Y'));

        for ($i = 0; $i < 3; $i++) {
            $metadata = new Metadata();
            $metadata->setReferential($this->getReference(ReferentialFixtures::REFERENTIAL_REFERENCE));
            $metadata->setEntry('entry ' . $i);
            $metadata->setValue(mt_rand(10, 100));
            $metadata->setStartDate(new \DateTime());
            $metadata->setEndDate($end_date);

            $em->persist($metadata);
        }

        $em->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            ReferentialFixtures::class
        ];
    }
}
