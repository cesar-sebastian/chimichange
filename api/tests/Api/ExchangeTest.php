<?php

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use App\Entity\Rate;

class ExchangeTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testValidExchange()
    {
        $client = self::createClient();

        $rate1 = new Rate();
        $rate1->setCurrency('ARS');
        $rate1->setValue(111.864903);

        $rate2 = new Rate();
        $rate2->setCurrency('USD');
        $rate2->setValue(1.203221);

        $manager = self::$container->get('doctrine')->getManager();
        $manager->persist($rate1);
        $manager->persist($rate2);
        $manager->flush();

        $token = $this->createAccountAndLogIn($client, 'clientTest@chimichange.com', 'clientTest');

        //deposit
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

        //exchange
        $client->request('POST', '/exchange', [
            'json' => [
                'currencyFrom' => 'ARS',
                'currencyTo' => 'USD',
                'amount' => 5000.00
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            "@type" => "Cash",
            "currency" => "ARS",
            "amount" => "2000.0000"
        ]);

        //check
        $resp = $client->request('GET', '/accounts?page=1&user.email=clientTest@chimichange.com', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            "@type" => "hydra:Collection",
            "hydra:member" => [
                [
                    "cashes" => [
                        [
                            "currency" => "ARS",
                            "amount" => "2000.0000"
                        ],
                        [
                            "currency" => "USD",
                            "amount" => "53.7801"
                        ]
                    ],
                    "transactions" => [
                        [
                            "operation" => "DEPOSIT",
                            "currencyFrom" => "ARS",
                            "currencyTo" => "ARS",
                            "rate" => "1.0000",
                            "amountFrom" => "0.0000",
                            "amountTo" => "7000.0000"
                        ],
                        [
                            "operation" => "EXCHANGE",
                            "currencyFrom" => "ARS",
                            "currencyTo" => "USD",
                            "rate" => "0.0108",
                            "amountFrom" => "5000.0000",
                            "amountTo" => "53.7801"
                        ]
                    ]
                ]
            ]
        ]);

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
