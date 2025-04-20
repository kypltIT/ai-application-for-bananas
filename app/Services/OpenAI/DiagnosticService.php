<?php
// Created a new Diagnostic Service using OpenAI

namespace App\Services\OpenAI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiagnosticService
{
    protected $apiKey;
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';
    protected $model = 'gpt-4o';

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    /**
     * Run a diagnostic analysis on the provided data
     *
     * @param array $data Data to analyze
     * @param string $prompt Additional context for the analysis
     * @return array Analysis results
     */
    public function runDiagnostic(array $data, string $prompt = ''): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You must search on web. You are a diagnostic system that analyzes data and provides insights, recommendations, and potential issues.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->formatPrompt($data, $prompt)
                    ]
                ],
                'temperature' => 0.2, // Lower temperature for more factual responses
                'max_tokens' => 2048
            ]);

            $result = $response->json();

            if (isset($result['choices'][0]['message']['content'])) {
                return [
                    'success' => true,
                    'analysis' => $result['choices'][0]['message']['content'],
                    'model' => $result['model'] ?? $this->model,
                ];
            }

            Log::error('OpenAI API Error', $result);
            return [
                'success' => false,
                'error' => 'Failed to get proper response from OpenAI',
                'details' => $result
            ];
        } catch (\Exception $e) {
            Log::error('OpenAI API Exception', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Error connecting to OpenAI API',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Format data into a prompt that OpenAI can process
     */
    private function formatPrompt(array $data, string $additionalPrompt): string
    {
        $dataString = json_encode($data, JSON_PRETTY_PRINT);

        $promptBase = "Analyze the following data and provide a diagnostic assessment:\n\n";
        $promptBase .= "DATA:\n$dataString\n\n";

        if (!empty($additionalPrompt)) {
            $promptBase .= "ADDITIONAL CONTEXT:\n$additionalPrompt\n\n";
        }

        $promptBase .= "Please provide:\n";
        $promptBase .= "1. A JSON block with forecast, regional performance, and competitor comparison.\n";
        $promptBase .= "2. Key findings with supporting data.\n";
        $promptBase .= "3. Reasons for revenue changes (increase/decrease).\n";

        return $promptBase;
    }
}
