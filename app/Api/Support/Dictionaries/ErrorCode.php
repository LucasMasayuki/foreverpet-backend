<?php

namespace App\Api\Support\Dictionaries;

enum ErrorCode: string
{
    /**
     * Código de Erros retornado na API
     */
    case BadRequest = 'BAD_REQUEST';
    case NotFound = 'NOT_FOUND';
    case Conflict = 'CONFLICT';
    case MethodNotAllowed = 'METHOD_NOT_ALLOWED';
    case InvalidSession = 'INVALID_SESSION';
    case UnprocessableEntity = 'UNPROCESSABLE_ENTITY';
    case TooManyRequests = 'TOO_MANY_REQUESTS';
    case InternalServerError = 'INTERNAL_SERVER_ERROR';
    case MissingHeaders = 'MISSING_HEADERS';
    case MaxUploadSizeExceeded = 'MAX_UPLOAD_SIZE_EXCEEDED';
    case Unauthorized = 'UNAUTHORIZED';
    case InvalidScope = 'INVALID_SCOPE';
    case Forbidden = 'FORBIDDEN';
    case UneditableEntity = 'UNEDITABLE_ENTITY';
    case UndeletableEntity = 'UNDELETABLE_ENTITY';
    case UndeletedResourceAssociation = 'UNDELETED_RESOURCE_ASSOCIATION';
    case PaymentRequired = 'PAYMENT_REQUIRED';

    // Legacy retro-compatibility
    public const BAD_REQUEST = self::BadRequest->value;
    public const NOT_FOUND = self::NotFound->value;
    public const METHOD_NOT_ALLOWED = self::MethodNotAllowed->value;
    public const INVALID_SESSION = self::InvalidSession->value;
    public const UNPROCESSABLE_ENTITY = self::UnprocessableEntity->value;
    public const TOO_MANY_REQUESTS = self::TooManyRequests->value;
    public const INTERNAL_SERVER_ERROR = self::InternalServerError->value;
    public const MISSING_HEADERS = self::MissingHeaders->value;
    public const MAX_UPLOAD_SIZE_EXCEEDED = self::MaxUploadSizeExceeded->value;
    public const UNAUTHORIZED = self::Unauthorized->value;
    public const INVALID_SCOPE = self::InvalidScope->value;
    public const FORBIDDEN = self::Forbidden->value;
    public const UNEDITABLE_ENTITY = self::UneditableEntity->value;
    public const UNDELETABLE_ENTITY = self::UndeletableEntity->value;
    public const UNDELETED_RESOURCE_ASSOCIATION = self::UndeletedResourceAssociation->value;
    public const CONFLICT = self::Conflict->value;
    public const PAYMENT_REQUIRED = self::PaymentRequired->value;
}

