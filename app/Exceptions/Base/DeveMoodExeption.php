<?php

namespace App\Exceptions\Base;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

trait DeveMoodExeption
{


    private function handelMessage($e){

        $info['message'] = $e->getMessage();
        $info['file'] = $e->getFile();
        $info['line'] = $e->getLine();

        return $info;
    }




    private function handleException(\Exception $e)
    {
        switch (true) {

            case $e instanceof UnauthorizedHttpException:
                return $this->apiResponse($this->handelMessage($e), false, 'Token not provided', Response::HTTP_UNAUTHORIZED);

            case $e instanceof ModelNotFoundException:
                $modelName = strtolower(class_basename($e->getModel()));
                return $this->apiResponse($this->handelMessage($e) ,  false, 'Database ERROR : this '.$modelName.' not found!   /// ' , Response::HTTP_NOT_FOUND);
            case $e instanceof RelationNotFoundException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  ', Response::HTTP_NOT_FOUND);
            case $e instanceof InvalidCastException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  ', Response::HTTP_NOT_FOUND);
            case $e instanceof JsonEncodingException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  ', Response::HTTP_NOT_FOUND);
            case $e instanceof HttpResponseException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  ', Response::HTTP_NOT_FOUND);
            case $e instanceof PostTooLargeException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  ', Response::HTTP_NOT_FOUND);
            case $e instanceof ThrottleRequestsException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  ', Response::HTTP_NOT_FOUND);
            case $e instanceof TokenMismatchException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  ', Response::HTTP_NOT_FOUND);
            case $e instanceof RequestException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  ', Response::HTTP_NOT_FOUND);
            case $e instanceof ConnectionException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  ', Response::HTTP_NOT_FOUND);
            case $e instanceof QueryException:
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    preg_match('/Duplicate entry \'(.*?)\' for key \'(.*?)\'/', $e->getMessage(), $matches);
                    return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  [ '. $matches[1] . ' ] is already taken', Response::HTTP_NOT_FOUND);
                } else {
                    return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested resource was not found :  ', Response::HTTP_NOT_FOUND);
                }
            case $e instanceof AuthenticationException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'Unauthenticated :  ', Response::HTTP_UNAUTHORIZED);
            case $e instanceof AuthorizationException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'You are not authorized to perform this action :  ', Response::HTTP_FORBIDDEN);
            case $e instanceof UnauthorizedException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'You are not authorized to perform this action :  ', Response::HTTP_FORBIDDEN);
            case $e instanceof RelationNotFoundException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The requested relation was not found :  ', Response::HTTP_NOT_FOUND);
            case $e instanceof InvalidCastException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'An invalid cast occurred :  ', Response::HTTP_INTERNAL_SERVER_ERROR);
            case $e instanceof JsonEncodingException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'An error occurred while encoding the JSON data :  ', Response::HTTP_INTERNAL_SERVER_ERROR);
            case $e instanceof RequestException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'An error occurred while sending the request :  ', Response::HTTP_INTERNAL_SERVER_ERROR);
            case $e instanceof ConnectionException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'An error occurred while processing the request :  ', Response::HTTP_INTERNAL_SERVER_ERROR);
            case $e instanceof ThrottleRequestsException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'Too many requests :  ', Response::HTTP_TOO_MANY_REQUESTS);
            case $e instanceof TokenMismatchException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The provided CSRF token is invalid :  ', Response::HTTP_UNPROCESSABLE_ENTITY);
            case $e instanceof HttpResponseException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'An HTTP response exception occurred :  ', Response::HTTP_INTERNAL_SERVER_ERROR);
            case $e instanceof PostTooLargeException:
                return $this->apiResponse($this->handelMessage($e) ,  false, 'The uploaded file is too large :  ', Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
            case $e instanceof \Exception && strpos($e->getMessage(), 'SYSTEM_CLIENT_ERROR') === 0 :
                // remove the SYSTEM_CLIENT_ERROR from the message
                $message = substr($e->getMessage(), strlen('SYSTEM_CLIENT_ERROR :'));
                return $this->apiResponse(NULL ,  false, $message , Response::HTTP_UNAUTHORIZED);
            default:
                return $this->apiResponse($this->handelMessage($e) , false, 'An unexpected error occurred :  ', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
