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
      // Consulta única para personas (postulantes, tutores, tribunales)
    public function getPersonaByCI($ci)
    {
        $url = "{$this->baseUrl}/postulante-textil/ci/{$ci}";

        $response = Http::withToken($this->token)->get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}

