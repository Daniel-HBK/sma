<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateMessageRequest;
use App\Http\Requests\V1\RetrieveMessageRequest;
use App\Services\V1\MessageService;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    private $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Create a new message.
     *
     * @param CreateMessageRequest $request
     * @return JsonResponse
     */
    public function store(CreateMessageRequest $request): JsonResponse
    {
        try {
            $result = $this->messageService->createMessage($request->validated());
            return response()->json([
                'success' => true,
                'message' => __('app.messageCreated'),
                'identifier' => $result['identifier'],
                'decryption_key' => $result['decryption_key'],
            ], 201);
        } catch (\Exception $e) {
            // \Log::error('Failed to create message', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => __('app.messageCreateFailed'),
                // 'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve a message.
     *
     * @param RetrieveMessageRequest $request
     * @param string $identifier
     * @return JsonResponse
     */
    public function show(RetrieveMessageRequest $request, string $identifier): JsonResponse
    {
        try {
            $message = $this->messageService->retrieveMessage(
                $identifier,
                $request->input('recipient_identifier'),
                $request->input('decryption_key')
            );

            if (!$message) {
                return response()->json([
                    'success' => false,
                    'error' => __('app.messageNotFound')
                ], 404);
            }

            return response()->json($message);
        } catch (\Exception $e) {
            // \Log::error('Failed to retrieve message', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => __('app.failedToRetrieveMessage'),
                // 'details' => $e->getMessage()
            ], 500);
        }
    }
}