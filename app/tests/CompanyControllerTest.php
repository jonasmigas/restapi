<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class CompanyControllerTest extends TestCase
{
    public function testTop10Companies()
    {
        $client = new Client();
        $response = $client->get('http://localhost/api/topcompanies');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonResponse('top10Companies', $response, Response::HTTP_OK);
    }


    protected function assertJsonResponse($endPoint, $response, $statusCode = 200)
    {
        var_dump($endPoint . ': status code: ' . $response->getStatusCode());
        $this->assertEquals(
            $statusCode,
            $response->getStatusCode(),
        );
    }
}
