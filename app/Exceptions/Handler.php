<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param $request
     * @param Throwable $e
     * @return Response|JsonResponse|RedirectResponse
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response|JsonResponse|RedirectResponse
    {
        if ($e instanceof NotFoundHttpException && $request->wantsJson()) {
            return response()->json([
                'statue'=>404,
                'message' => trans('The requested url not found !!'),
            ], 404);
        }

        if ($e instanceof MethodNotAllowedHttpException && $request->wantsJson()) {
            return response()->json(['message' => 'Method not allowed.','status'=>405],405);
        }

        if ($e instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json(['status' => 404,'message' => 'No results found with this id.'],404);
        }

        if ($e instanceof ValidationException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('The given data was invalid.'),
                'errors' => $this->response($e->validator->getMessageBag()->toArray()),
            ], 422);
        }

        if ($e instanceof QueryException && $request->wantsJson()) {
            return response()->json(['message'=>'Failed to connect database.'.$e->getMessage(), 'status'=>500],500);
        }

        if($request->wantsJson()){
            return response()->json(['message'=>$e->getMessage().' #'.$e->getLine(), 'status'=>500],500);
        }

        return parent::render($request, $e);
    }

    /**
     * Format the validation message
     *
     * @param array $errors
     * @return array
     */
    protected function response(array $errors): array
    {
        $transformed = [];

        foreach ($errors as $field => $message) {
            $transformed[] = [
                'field' => $field,
                'message' => $message[0]
            ];
        }
        return $transformed;
    }
}
