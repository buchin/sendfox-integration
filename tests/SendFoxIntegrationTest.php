<?php

namespace Buchin\SendFoxIntegration\Tests;

use Illuminate\Support\Facades\Http;
use SendFoxIntegration\SendFoxService;
use Tests\TestCase;

class SendFoxIntegrationTest extends TestCase
{
    public function test_create_contact_successfully()
    {
        // Mock the HTTP client
        Http::fake([
            'https://api.sendfox.com/contacts' => Http::response(['success' => true], 200),
        ]);

        // Create an instance of the service
        $service = new SendFoxService();

        // Mock user data
        $user = (object) [
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
        ];

        // Call the service method
        $response = $service->createContact($user);

        // Assert the response was successful
        $this->assertTrue($response->successful());
        $this->assertEquals(['success' => true], $response->json());
    }

    public function test_create_contact_failure()
    {
        // Mock the HTTP client
        Http::fake([
            'https://api.sendfox.com/contacts' => Http::response(['error' => 'Invalid data'], 400),
        ]);

        // Create an instance of the service
        $service = new SendFoxService();

        // Mock user data
        $user = (object) [
            'email' => 'invalid-email',
            'first_name' => '',
            'last_name' => '',
        ];

        // Call the service method
        $response = $service->createContact($user);

        // Assert the response failed
        $this->assertFalse($response->successful());
        $this->assertEquals(['error' => 'Invalid data'], $response->json());
    }
}