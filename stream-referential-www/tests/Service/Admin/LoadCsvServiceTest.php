<?php

namespace App\Tests\Service\Admin;

use App\Entity\ReferentialType;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoadCsvServiceTest extends WebTestCase
{
    private $client;

    public function testToRepositories()
    {
        // When
        $this->client->request('GET', '/admin/referential/manage/XXX');

        // Then
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Given
        $form_data = [
            'load[csv]' => 'tests/data/in/referential.csv'
        ];

        // When
        $this->client->submitForm('load[save]', $form_data);

        // Then
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->loadData();

        parent::setUp();
    }

    private function loadData()
    {
        try {
            $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
            $em->persist((new ReferentialType())
                ->setId('XXX')
                ->setDescription('Test'));
            $em->flush();
        } catch (ORMException $e) {
            dump($e);
        }
    }
}
