<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class ReviewControllerTest extends TestCase
{
    public function testReviews()
    {
        $client = new Client();
        $response = $client->get('http://localhost/api/reviews');
        $this->assertEquals(200, $response->getStatusCode());
        //var_dump($response->getBody()->getContents());
    }

    public function testSubmitReview()
    {
        $client = new Client();
        $response = $client->post(
            'http://localhost/api/submitreview',
            [
                RequestOptions::JSON => [
                    "title" => "Another Review",
                    "user" => "1",
                    "company" => "1",
                    "culture" => "5",
                    "management" => "10",
                    "work_live_balance" => "5",
                    "career_development" => "5",
                    "pro" => "teste",
                    "contra" => "sem contras",
                    "suggestions" => "teste"
                ]
            ]
        );
        $this->assertJsonResponse($response, 201);
        //var_dump($response->getBody()->getContents());
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        var_dump($response->getStatusCode());
        $this->assertEquals(
            $statusCode,
            $response->getStatusCode(),
        );

        // $this->assertTrue(
        //     $response->getHeader('Content-Type'),
        //     $response->headers
        // );
    }
}
