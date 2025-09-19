<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TextilApiService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.textil.url');
        $this->token   = config('services.textil.token');
    }

    public function getPostulanteByCI($ci)
    {
        $url = "{$this->baseUrl}/postulante-textil/ci/{$ci}";

        $response = Http::withToken($this->token)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            return $data;
        }

        return null;
    }
}

