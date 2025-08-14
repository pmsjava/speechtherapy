<?php
namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

class BookingFlowTest extends PantherTestCase
{
    public function testAppointmentFormRenders()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/appointment/new');
        $this->assertSelectorExists('form');
    }

    public function testSwaggerIsReachable()
    {
        $client = static::createPantherClient();
        $client->request('GET', '/api/doc');
        $this->assertPageTitleContains('Logopedia API');
    }
}
