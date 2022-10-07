<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TwiteeApiTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_welcome()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('api/v1/onboard/welcome');
        $response->assertStatus(200);
    }

    /** Test Sign up process
     *
     */
    public function test_sign_up()
    {
        $fields = [
            'email' => "collins@gmail.com",
            "password" => "password"
        ];
        $response = $this->json('POST','api/v1/onboard/signup', $fields);
        $response->assertStatus(200);

    }

    /** Test Login process
     *
     */
    public function test_login()
    {
        $fields = [
            'email' => 'pizcmr@gmail.com',
            "password" => "password"
        ];
        $response = $this->json('POST','api/v1/onboard/login', $fields);
        $response->assertStatus(200);

        return $response["data"]["access_token"];
    }

    /**
     * Create a post
     */
    public function test_create_post()
    {
        $fields = [
            "twit" => "Scroll to the tweet you want to quote. If you want to be able to quote the tweet while adding"
        ];

        $token = $this->test_login();

        //create a post
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', 'api/v1/posts/create', $fields);
        $response->assertStatus(200);

        return $token;
    }

    /**
     * Create a comment
     */
    public function test_create_comment()
    {
        $fields = [
            "comment" => "Hello everyone, I'm hosting a Twitter space Tomorrow morning by 10am tagged Designing for Fintech  set a reminder with this link"
        ];

        $token = $this->test_create_post();

        //create a comment
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', 'api/v1/posts/1/comment', $fields);
        $response->assertStatus(200);

        Log::info($token);
        Log::info('p', (array)$response);
    }

    /**
     * delete a post
     */
    public function test_delete_post()
    {

        $token = $this->test_create_post();

        //delete a post
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', 'api/v1/posts/1/delete');
        $response->assertStatus(200);

        Log::info($token);
        Log::info('p', (array)$response);
    }
}
