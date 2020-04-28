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
    }

    protected function setUp(): void
    {
        $this->client = self::createClient();

        parent::setUp();
    }
}
