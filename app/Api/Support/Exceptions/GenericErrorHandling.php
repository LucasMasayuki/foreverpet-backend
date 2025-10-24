<?php

namespace App\Api\Support\Exceptions;

use App\Api\Support\Contracts\CustomApiException;
use App\Api\Support\Dictionaries\ErrorCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class GenericErrorHandling
{
    private static int $statusCode;

    private static string $message;

    private static ?string $code;

    private static array $meta;

    public const CODES = [
        \ParseError::class => ErrorCode::INTERNAL_SERVER_ERROR,
        \Error::class => ErrorCode::INTERNAL_SERVER_ERROR,
        \Illuminate\Auth\AuthenticationException::class => ErrorCode::UNAUTHORIZED,
        NotFoundHttpException::class => ErrorCode::NOT_FOUND,
        ModelNotFoundException::class => ErrorCode::NOT_FOUND,
        MethodNotAllowedHttpException::class => ErrorCode::METHOD_NOT_ALLOWED,
        TokenMismatchException::class => ErrorCode::INVALID_SESSION,
    ];

    public static function render($request, Throwable $exception): array
    {
        self::resolveStatusCode($exception);
        self::resolveErrorCode($exception);
        self::resolveMessage($exception);
        self::resolveMeta($exception);

        if ($exception::class === HttpException::class) {
            self::resolveHttpException($exception);
        }

        if (in_array($exception::class, [\ParseError::class, \Error::class]) || ! self::$code && ! ($exception instanceof CustomApiException)) {
            report($exception);
        }

        /**
         * Por padrão retorna BAD_REQUEST, caso não entre em nenhuma regra
         */
        $defaultStatus = strtolower(Str::snake(ResponseAlias::$statusTexts[self::$statusCode]));
        $code = strtolower(self::$code ?? self::CODES[$exception::class] ?? $defaultStatus);

        $data = [
            'code' => $code,
            'message' => self::$message,
            'meta' => self::$meta,
        ];

        return [$data, self::$statusCode];
    }

    private static function resolveStatusCode(Throwable $exception): void
    {
        if ($exception instanceof CustomApiException) {
            self::$statusCode = $exception->getStatusCode();
        } else {
            self::$statusCode = match ($exception::class) {
                \Illuminate\Auth\AuthenticationException::class => ResponseAlias::HTTP_UNAUTHORIZED,
                ModelNotFoundException::class, NotFoundHttpException::class => ResponseAlias::HTTP_NOT_FOUND,
                MethodNotAllowedHttpException::class => ResponseAlias::HTTP_METHOD_NOT_ALLOWED,
                TokenMismatchException::class => ResponseAlias::HTTP_FORBIDDEN,
                default => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            };
        }
    }

    private static function resolveErrorCode(Throwable $exception): void
    {
        self::$code = null;

        if ($exception instanceof CustomApiException) {
            self::$code = $exception->getErrorCode();
        }
    }

    private static function resolveMessage(Throwable $exception): void
    {
        if ($exception instanceof CustomApiException) {
            self::$message = $exception->getMessage();
        } else {
            self::$message = match ($exception::class) {
                \Illuminate\Auth\AuthenticationException::class => 'Unauthorized',
                ModelNotFoundException::class, NotFoundHttpException::class => ResponseAlias::$statusTexts[ResponseAlias::HTTP_NOT_FOUND],
                MethodNotAllowedHttpException::class => 'Method is not allowed for the requested route',
                TokenMismatchException::class => 'Invalid session',
                default => ResponseAlias::$statusTexts[ResponseAlias::HTTP_INTERNAL_SERVER_ERROR]
            };
        }
    }

    private static function resolveMeta(Throwable $exception): void
    {
        self::$meta = [];

        if ($exception instanceof CustomApiException) {
            self::$meta = $exception->getMeta();
        }
    }

    private static function resolveHttpException(HttpException $exception): void
    {
        self::$statusCode = $exception->getStatusCode();

        self::$code = match (true) {
            self::$statusCode == ResponseAlias::HTTP_TOO_MANY_REQUESTS => ErrorCode::TOO_MANY_REQUESTS,
            self::$statusCode >= ResponseAlias::HTTP_INTERNAL_SERVER_ERROR => ErrorCode::INTERNAL_SERVER_ERROR,
            default => ErrorCode::INTERNAL_SERVER_ERROR
        };

        self::$message = match (true) {
            self::$statusCode == ResponseAlias::HTTP_TOO_MANY_REQUESTS => $exception->getMessage(),
            self::$statusCode >= ResponseAlias::HTTP_INTERNAL_SERVER_ERROR => 'Internal Server Error',
            default => 'Internal Server Error'
        };
    }
}

