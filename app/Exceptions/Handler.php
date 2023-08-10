<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $this->renderable(function(AccessDeniedHttpException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'You are unauthorized from performing this action',
                'error_message' => $e->getMessage()
            ], 401);
        });

        $this->renderable(function(AuthenticationException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'User is unauthenticated',
                'error_message' => $e->getMessage()
            ], 401);
        });
        
        $this->renderable(function(ValidationException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Validation error',
                'error_message' => $e->validator->errors()
            ], 422);
        });
        $this->renderable(function(QueryException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' =>'Error connecting to database',
                'error_message' => $e->getMessage()
            ], 404);
        }); 
        $this->renderable(function(MethodNotAllowedHttpException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' =>'Method is not allowed',
                'error_message' => $e->getMessage()
            ], 404);
        }); 
        $this->renderable(function(NotFoundHttpException $e, Request $request) {
            if ($e->getPrevious() instanceof ModelNotFoundException) {
                /** @var ModelNotFoundException $modelNotFound */
                $modelNotFound = $e->getPrevious();
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'message' => trim(str_replace('App\\Models\\','',$modelNotFound->getModel())).' not found.',
                    'error_message' => $e->getMessage()
                ], 404);
            }
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Not found',
                'error_message' => $e->getMessage()
            ], 404);
        });
        $this->renderable(function(BindingResolutionException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Error getting resource',
                'error_message' => $e->getMessage()
            ], 401);
        });
        $this->renderable(function(HttpException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => $e->getMessage(),
                'error_message' => $e->getMessage()
            ], 401);
        });
    }
}
