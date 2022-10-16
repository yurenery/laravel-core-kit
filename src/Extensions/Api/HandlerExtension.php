<?php

namespace AttractCores\LaravelCoreKit\Extensions\Api;

use AttractCores\LaravelCoreClasses\Libraries\ServerResponse;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;

/**
 * Trait HandlerExtension
 *
 * @version 1.0.0
 * @date    03/12/2018
 * @author  Yure Nery <yurenery@gmail.com>
 */
trait HandlerExtension
{


    /**
     * Render oauth errors.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return ServerResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return app('kit.response')
            ->status(401)
            ->errors([ $this->convertExceptionToArray($exception) ]);
    }

    /**
     * Convert the given exception to an array.
     *
     * @param Throwable $e
     *
     * @return array
     */
    protected function convertExceptionToArray(Throwable $e)
    {
        $isDebug = config('app.debug');

        return app('kit.response')->getErrorArrayStructure(
            method_exists($e, 'getErrorType') ? $e->getErrorType() : class_basename($e),
            __($e->getMessage()),
            'non_field_error',
            [],
            $isDebug ? $e->getFile() : NULL,
            $isDebug ? $e->getLine() : NULL,
            $isDebug ? collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, [ 'args' ]);
            })->all() : []
        );
    }

    /**
     * Prepare a JSON response for the given exception.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable                $e
     *
     * @return ServerResponse
     */
    protected function prepareJsonResponse($request, Throwable $e)
    {
        return app('kit.response')
            ->status($this->getExceptionStatusCode($e))
            ->withHeaders($this->isHttpException($e) ? $e->getHeaders() : [])
            ->errors([ $this->convertExceptionToArray($e) ]);
    }

    /**
     * Return exception status code.
     *
     * @param Throwable $e
     *
     * @return int
     */
    protected function getExceptionStatusCode(Throwable $e)
    {
        switch ( true ) {
            case ( $e instanceof OAuthServerException ):
                return 401;
                break;
            default:
                return $this->isHttpException($e) ? $e->getStatusCode() : 500;
                break;
        }
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param \Illuminate\Validation\ValidationException $e
     * @param \Illuminate\Http\Request                   $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|ServerResponse
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        return $request->expectsJson()
            ?
            $this->prepareValidationFails($e->validator->failed(), $request, $e->validator->getMessageBag(),
                $e->errors())
            : $this->invalid($request, $e);
    }

    /**
     * Prepare validation rules.
     *
     * @param                                         $failed
     * @param \Illuminate\Http\Request                $request
     * @param MessageBag                              $messages
     * @param null                                    $errorsValidator
     *
     * @return ServerResponse
     */
    protected function prepareValidationFails($failed, Request $request, MessageBag $messages, $errorsValidator = NULL)
    {
        /** @var ServerResponse $response */
        $response = app('kit.response')->status(422);

        foreach ( $failed as $field => $rules ) {
            $count = 0;
            $errors = [];
            $values = [];
            foreach ( $rules as $rule => $parameters ) {
                $errors[] = $messages->get($field)[ $count ];
                $fieldValue = $request->input($field);
                $values = is_array($fieldValue) ? $fieldValue : [ $fieldValue ];
                $rules[] = Str::snake($rule);
                $count++;
            }

            $response->pushError('validation', $errors, $field, $values);
        }

        if ( empty($failed) && ! empty($errorsValidator) ) {
            foreach ( $errorsValidator as $field => $errorMessages ) {
                $response->pushError('validation', $errorMessages, $field, $request->input($field, []));
            }
        }

        return $response;
    }

}
