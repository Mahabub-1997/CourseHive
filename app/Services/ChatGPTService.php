<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatGPTService
{
    protected $maxRetries = 3; // Maximum retries on 429

    public function ask(string $prompt): string
    {
        $apiKey = config('services.openai.key');
        $attempt = 0;

        do {
            $attempt++;

            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer $apiKey",
                    'Content-Type' => 'application/json',
                ])->timeout(30)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            ['role' => 'system', 'content' => 'Only respond with plain text. No JSON, HTML, or formatting.'],
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'temperature' => 0.7
                    ]);

                // Log full response
                Log::info('OpenAI HTTP Status: ' . $response->status());
                Log::info('OpenAI Body: ' . $response->body());

                if ($response->status() == 429) {
                    // Too many requests, wait before retry
                    sleep(2);
                    continue;
                }

                if ($response->failed()) {
                    Log::error('OpenAI API Error: ' . $response->body());
                    return 'No response from OpenAI API (HTTP ' . $response->status() . ')';
                }

                $result = $response->json();

                if (!isset($result['choices'][0]['message']['content'])) {
                    Log::warning('OpenAI returned unexpected format', $result);
                    return 'No valid response from OpenAI API';
                }

                return $result['choices'][0]['message']['content'];

            } catch (\Exception $e) {
                Log::error('OpenAI Exception: ' . $e->getMessage());
                return 'Error communicating with OpenAI API';
            }

        } while ($attempt < $this->maxRetries);

        return 'Max retries reached. Please try again later.';
    }
}




