<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if (!function_exists('apiSuccessResponse')) {
      /**
       * Generate a success API response.
       *
       * @param mixed $data
       * @param string $message
       * @param int $statusCode
       * @return JsonResponse
       */
      function apiSuccessResponse(string $message = 'Success',  int $statusCode = 200, $data = null): JsonResponse
      {
            $response = [
                  'success' => true,
                  'message' => $message,
            ];

            if (!is_null($data)) {
                  $response['data'] = $data;
            }

            return response()->json($response, $statusCode);
      }
}

if (!function_exists('apiErrorResponse')) {
      /**
       * Generate an error API response.
       *
       * @param string $message
       * @param int $statusCode
       * @param mixed $details
       * @return JsonResponse
       */
      function apiErrorResponse(string $message, int $statusCode = 400, $e = null): JsonResponse
      {
            $errorCodes = [
                  400 => 'BAD_REQUEST',
                  401 => 'UNAUTHORIZED',
                  403 => 'FORBIDDEN',
                  404 => 'NOT_FOUND',
                  422 => 'VALIDATION_ERROR',
                  500 => 'SERVER_ERROR',
            ];

            $response = [
                  'success' => false,
                  'code' => $errorCodes[$statusCode] ?? 'UNKNOWN_ERROR',
                  'message' => $message,
            ];

            Log::warning($response);

            if (!is_null($e)) {
                  Log::error($message, [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage()
                  ]);
            }

            return response()->json($response, $statusCode);
      }
}
