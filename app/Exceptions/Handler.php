<?php

namespace App\Exceptions;

use App\Api\Support\Contracts\CustomApiException;
use App\Api\Support\Exceptions\GenericErrorHandling;
use App\Api\Support\Exceptions\ValidationErrorHandling;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        HttpException::class,
        ValidationException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
        CustomApiException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Rota de Validação
        if ($e instanceof ValidationException) {
            return parent::render($request, $e);
        }

        // Se for rota da API
        if ($this->requestIsApi($request)) {
            [$errors, $status] = GenericErrorHandling::render($request, $e);

            return response()->json($errors, $status);
        }

        // Rota inexistente
        if ($e instanceof NotFoundHttpException) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Not Found',
                ], 404);
            } elseif ($request->isJson()) {
                return parent::render($request, $e); // se for API
            }
        }

        // Método inválido Ex: POST em uma rota que só aceita GET
        if ($e instanceof MethodNotAllowedHttpException) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Method is not allowed for the requested route',
                ], 405);
            } elseif ($request->isJson()) {
                return parent::render($request, $e); // se for API
            }
        }

        // Tentando editar um elemento que não existe mais no banco de dados
        if ($e instanceof ModelNotFoundException) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Model Not Found',
                ], 404);
            } elseif ($request->isJson()) {
                return parent::render($request, $e); // se for API
            }
        }

        // Caiu a sessão do usuário e o token ficou inválido
        if ($e instanceof TokenMismatchException) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Sessão inválida',
                ], 403);
            }
        }

        // Atingiu o limite de requisições API
        if ($e instanceof HttpException) {
            if ($e->getStatusCode() == 429) {
                return response()->json([
                    'success' => 0,
                    'message' => $e->getMessage(),
                ], 429)->withHeaders($e->getHeaders());
            }

            if ($e->getStatusCode() == 503) {
                // sistema temporariamente indisponível
                return parent::render($request, $e);
            }
        }

        return parent::render($request, $e);
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        /**
         * Formato de retorno padronizado da API
         */
        if ($this->requestIsApi($request)) {
            return response()->json(ValidationErrorHandling::render($exception), $exception->status);
        }

        return response()->json($exception->errors(), $exception->status);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function requestIsApi(Request $request): bool
    {
        return $request->routeIs('api.v1.*') || Str::startsWith($request->path(), 'api/v1/');
    }
}

