<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\V1\RecipientService;
use Illuminate\Http\JsonResponse;

class RecipientController extends Controller
{
    private RecipientService $recipientService;

    public function __construct(RecipientService $recipientService)
    {
        $this->recipientService = $recipientService;
    }

    /**
     * Show the details of a specific recipient by identifier.
     *
     * @param string $identifier
     * @return JsonResponse
     */
    public function show(string $identifier): JsonResponse
    {
        $recipient = $this->recipientService->retrieveRecipientByIdentifier($identifier);

        if (!$recipient) {
            return response()->json(['status' => false, 'error' => __('app.recipientNotFound')], 404);
        }

        return response()->json($recipient);
    }
}