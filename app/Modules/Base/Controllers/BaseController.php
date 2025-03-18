<?php

namespace App\Modules\Base\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected $service;

    /**
     * Respond with success
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function respondWithSuccess($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return ResponseHelper::success($data, $message, $code);
    }

    /**
     * Respond with error
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function respondWithError(string $message = 'Error', int $code = 400): JsonResponse
    {
        return ResponseHelper::error($message, $code);
    }
} 