<?php

namespace App\Http\Controllers;

use App\Services\AiBaristaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiBaristaController extends Controller
{
    public function __construct(
        protected AiBaristaService $aiService
    ) {}

    /**
     * Handle AI chat recommendations from web/mobile clients
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'context' => 'nullable|string|max:50',
            'table_number' => 'nullable|string|max:20',
        ]);

        $response = $this->aiService->chat(
            query: $validated['message'],
            context: $validated['context'] ?? 'order',
            tableNumber: $validated['table_number'] ?? null,
        );

        if (!empty($response['recommendations']) && is_array($response['recommendations'])) {
            $tableId = null;
            if (!empty($validated['table_number'])) {
                $tableId = \App\Models\Table::where('table_number', $validated['table_number'])->value('id');
            }

            \App\Models\AiConsultation::create([
                'table_id' => $tableId,
                'user_prompt' => $validated['message'],
                'ai_response' => $response['message'] ?? '',
                'recommended_ids' => collect($response['recommendations'])->pluck('id')->filter()->values()->all(),
                'is_converted' => false,
            ]);
        }

        return response()->json($response);
    }
}
