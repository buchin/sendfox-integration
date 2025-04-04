<?php
// Step 1: Create a new Laravel package structure for SendFox integration.
// Step 2: Move the logic for interacting with the SendFox API into a reusable service class.
// Step 3: Add configuration options for the SendFox API.

namespace Buchin\SendFoxIntegration;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendFoxService
{
    protected $token;
    protected $url;
    protected $list;

    public function __construct()
    {
        $this->token = config('sendfox.token');
        $this->url = config('sendfox.url');
        $this->list = config('sendfox.list_id');
    }

    public function createContact($user)
    {
        $response = Http::withToken($this->token)
            ->post($this->url, [
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'ip_address' => request()->ip(),
                'lists' => [$this->list],
                'contact_fields' => [],
            ]);

        Log::info('Created contact on SendFox', ['response' => $response->body()]);

        if ($response->failed()) {
            Log::error('Failed to create contact on SendFox', ['response' => $response->body()]);
        }

        return $response;
    }
}