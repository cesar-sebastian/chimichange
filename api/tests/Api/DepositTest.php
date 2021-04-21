<?php

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class DepositTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testValidDeposit()
    {
        $client = self::createClient();

        $token = $this->createAccountAndLogIn($client, 'clientTest@chimichange.com', 'clientTest');

        $client->request('POST', '/deposit', [
            'json' => [
                'amount' => 7000.00,
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            "amount" => "7000.0000"
        ]);

    }

    public function testInvalidDeposit()
    {
        $client = self::createClient();

        $token = $this->createAccountAndLogIn($client, 'clientTest@chimichange.com', 'clientTest');

        $client->request('POST', '/deposit', [
            'json' => [
                'amount' => "test"
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    private function createAccountAndLogIn($client, $email, $password)
    {
        //create account
        $response = $client->request('POST', '/accounts', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);

        $this->assertResponseIsSuccessful();

        //authenticate
        return $this->logIn($client, $email, $password);
    }

    private function logIn($client, $user, $password)
    {
        $resp = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => $user,
                'password' => $password
            ],
        ]);

        $this->assertResponseIsSuccessful();

        try {
            /**
             * @var ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response $resp
             */
            return $resp->toArray()['token'];
        } catch (Exception $e) {
            return false;
        }
    }

}
