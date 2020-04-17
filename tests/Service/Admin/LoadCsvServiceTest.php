<?php

namespace App\Tests\Service\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoadCsvServiceTest extends WebTestCase
{
    public function testToRepositories()
    {
        // Given
        $client = self::createClient();

        // When
        $client->request('GET', '/admin/referential/load/XXX');

        // Then
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Given
        $form_data = [
            'load_referential[csv]' => 'tests/data/in/referential.csv'
        ];

        // When
        $client->submitForm('load_referential[save]', $form_data);

        // Then
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
