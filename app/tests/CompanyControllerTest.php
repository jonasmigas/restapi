<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ReviewControllerTest extends TestCase
{
    public function testTop10Companies()
    {
        $client = new Client();
        $response = $client->get('http://localhost/api/topcompanies');
        $this->assertEquals(200, $response->getStatusCode());
        //var_dump($response->getBody()->getContents());
    }
}
