<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\HttpFoundation\Response;

class ReviewControllerTest extends TestCase
{
    public function testReviews()
    {
        $client = new Client();
        $response = $client->get('http://localhost/api/reviews');
        $this->assertJsonResponse('listReviews', $response, Response::HTTP_OK);
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
                    "management" => "2",
                    "work_live_balance" => "5",
                    "career_development" => "5",
                    "pro" => "teste",
                    "contra" => "sem contras",
                    "suggestions" => "teste"
                ]
            ]
        );
        $this->assertJsonResponse('submitReview', $response, Response::HTTP_CREATED);
    }

    public function testHighLowRating()
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
                    "management" => "2",
                    "work_live_balance" => "5",
                    "career_development" => "5",
                    "pro" => "teste",
                    "contra" => "sem contras",
                    "suggestions" => "teste"
                ]
            ]
        );
        $this->assertJsonResponse('submitReview', $response, Response::HTTP_CREATED);
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
