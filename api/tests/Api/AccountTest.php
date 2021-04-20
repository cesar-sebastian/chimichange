<?php


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class AccountTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateAccount(): void
    {
        $client = self::createClient();

        $response = $client->request('POST', '/accounts', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'clientTest@chimichange.com',
                'password' => 'clientTest',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
    }
}
