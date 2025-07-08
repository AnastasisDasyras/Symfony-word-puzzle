<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class DictionaryService
{
    private const API_BASE_URL = 'https://api.dictionaryapi.dev/api/v2/entries/en/';

    public function __construct(
        private HttpClientInterface $httpClient
    ) {}

    /**
     * Check if a word is valid by calling the Dictionary API
     */
    public function isValidWord(string $word): bool
    {
        $word = trim(strtolower($word));

        // Basic validation
        if (empty($word) || !preg_match('/^[a-z]+$/', $word)) {
            return false;
        }

        try {
            $response = $this->httpClient->request('GET', self::API_BASE_URL . $word);

            // If we get a 200 response, the word exists
            if ($response->getStatusCode() === 200) {
                return true;
            }

            // If we get a 404, the word doesn't exist
            if ($response->getStatusCode() === 404) {
                return false;
            }

            return false;
        } catch (TransportExceptionInterface | ClientExceptionInterface | ServerExceptionInterface $e) {
            return false;
        }
    }
}
