<?php

namespace App\Http\Controllers\API\ChatGpt;

use App\Http\Controllers\Controller;
use App\Services\ChatGPTService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatGPTService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Accept a prompt and return ChatGPT response
     */
    public function ask(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $answer = $this->chatService->ask($request->prompt);

        return response()->json([
            'success' => true,
            'response' => $answer
        ]);
    }
}
