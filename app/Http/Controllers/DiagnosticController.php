<?php
// Created a new Controller for handling Diagnostic requests using OpenAI

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosticRequest;
use App\Services\OpenAI\DiagnosticService;
use Illuminate\Http\JsonResponse;

class DiagnosticController extends Controller
{
    protected $diagnosticService;

    public function __construct(DiagnosticService $diagnosticService)
    {
        $this->diagnosticService = $diagnosticService;
    }

    /**
     * Run a diagnostic analysis on the provided data
     *
     * @param DiagnosticRequest $request
     * @return JsonResponse
     */
    public function analyze(DiagnosticRequest $request): JsonResponse
    {
        $data = $request->validated();
        $prompt = $data['prompt'] ?? '';

        // Remove prompt from data if it exists
        if (isset($data['prompt'])) {
            unset($data['prompt']);
        }

        $result = $this->diagnosticService->runDiagnostic($data, $prompt);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'analysis' => $result['analysis'],
                'model' => $result['model']
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'],
            'details' => $result['details'] ?? $result['message'] ?? null
        ], 500);
    }
}
