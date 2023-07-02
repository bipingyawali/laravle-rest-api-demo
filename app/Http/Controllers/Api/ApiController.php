<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel REST API Example",
 *      description="List of all API",
 * )
 * @OA\PathItem(path="/api")
 *  @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * )
 */
class ApiController extends Controller
{
    public function responseSuccess(string $message = "Successful", int $status_code = 200): JsonResponse
    {
        return response()->json([
            'status'  => $status_code   ,
            'message' => $message,
        ], $status_code);
    }

    function responseError($exception = null, $message = 'Failed', $status_code = 500): JsonResponse
    {
        if(in_array(config('app.env'),['local','development']) && $exception != null){
            $message = $exception->getMessage().' #'.$exception->getLine();
        }
        return response()->json([
            'status'  => $status_code,
            'message' => $message,
        ], $status_code);
    }
}
