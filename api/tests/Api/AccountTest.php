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

        $this->assertResponseIsSuccessful();
    }

    public function testCreateInvalidAccount(): void
    {
        $client = self::createClient();

        $response = $client->request('POST', '/accounts', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'invalid-email',
                'password' => 'clientTest',
            ],
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

//        self::assertJsonContains([
//            '@context' => '/contexts/ConstraintViolationList',
//            '@type' => 'ConstraintViolationList',
//            'hydra:title' => 'An error occurred',
//            'hydra:description' => 'email: This value is not a valid email address.',
//        ]);
    }
}
