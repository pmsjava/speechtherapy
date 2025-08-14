<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppointmentApiControllerTest extends WebTestCase
{
    private function loginAndGetToken($client): string
    {
        $client->request('POST', '/api/login', [], [], ['CONTENT_TYPE'=>'application/json'], json_encode([
            'username' => 'admin',
            'password' => 'adminpass'
        ]));
        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);
        return $data['token'];
    }

    public function testListEmpty()
    {
        $client = static::createClient();
        $token = $this->loginAndGetToken($client);

        $client->request('GET', '/api/appointments', server: [
            'HTTP_Authorization' => "Bearer $token"
        ]);
        $this->assertResponseIsSuccessful();
    }
}
