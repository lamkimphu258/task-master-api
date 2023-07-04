<?php

namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $instance = $this;

        Response::macro('ok', function ($data = []) {
            return response()->json(['data' => $data]);
        });

        Response::macro('created', function (
            string $message = 'Created successfully',
            array $data = []
        ) use ($instance) {
            return $instance->respondSuccess($message, $data, HttpResponse::HTTP_CREATED);
        });
    }

    /**
     * @param array<string,mixed> $data
     */
    public function respondSuccess(
        string $message = 'Success',
        array $data = [],
        int $status = HttpResponse::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
