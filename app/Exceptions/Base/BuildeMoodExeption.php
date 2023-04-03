<?php

namespace App\Exceptions\Base;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

trait BuildeMoodExeption
{
    private function handleException(\Exception $e)
    {
        switch (true) {
            case $e instanceof ModelNotFoundException:
                $modelName = strtolower(class_basename($e->getModel()));
                return $this->apiResponse(null, false, 'Database ERROR : this '.$modelName.' not found' , Response::HTTP_NOT_FOUND);
            case $e instanceof QueryException:
                return $this->apiResponse(null, false, 'A database error occurred.', Response::HTTP_INTERNAL_SERVER_ERROR);
            case $e instanceof AuthenticationException:
                return $this->apiResponse(null, false, 'Unauthenticated.', Response::HTTP_UNAUTHORIZED);
            case $e instanceof AuthorizationException:
            case $e instanceof UnauthorizedException:
                return $this->apiResponse(null, false, 'You are not authorized to perform this action.', Response::HTTP_FORBIDDEN);
            case $e instanceof RelationNotFoundException:
                return $this->apiResponse(null, false, 'The requested relation was not found.', Response::HTTP_NOT_FOUND);
            case $e instanceof InvalidCastException:
                return $this->apiResponse(null, false, 'An invalid cast occurred.', Response::HTTP_INTERNAL_SERVER_ERROR);
            case $e instanceof JsonEncodingException:
                return $this->apiResponse(null, false, 'An error occurred while encoding the JSON data.', Response::HTTP_INTERNAL_SERVER_ERROR);
            case $e instanceof RequestException:
            case $e instanceof ConnectionException:
                return $this->apiResponse(null, false, 'An error occurred while processing the request.', Response::HTTP_INTERNAL_SERVER_ERROR);
            case $e instanceof ThrottleRequestsException:
                return $this->apiResponse(null, false, 'Too many requests.', Response::HTTP_TOO_MANY_REQUESTS);
            case $e instanceof TokenMismatchException:
                return $this->apiResponse(null, false, 'The provided CSRF token is invalid.', Response::HTTP_UNPROCESSABLE_ENTITY);
            case $e instanceof HttpResponseException:
                return $this->apiResponse(null, false, 'An HTTP response exception occurred.', Response::HTTP_INTERNAL_SERVER_ERROR);
            case $e instanceof PostTooLargeException:
                return $this->apiResponse(null, false, 'The uploaded file is too large.', Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
            case $e instanceof \Exception && strpos($e->getMessage(), 'SYSTEM_CLIENT_ERROR') === 0 :
                return $this->apiResponse(null, false, $e->getMessage() , Response::HTTP_UNAUTHORIZED);
            default:
                return $this->apiResponse(null, false, 'An unexpected error occurred.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
