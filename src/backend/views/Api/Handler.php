<?php

namespace Kronas\Api;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    public function render($request, \Exception|\Throwable $e): JsonResponse
    {
        try {
            if ($e instanceof NotFoundHttpException) {
                throw new BaseApiException('Not Found', $e->getCode(), $e->getStatusCode());
            }
            if ($e instanceof MethodNotAllowedHttpException) {
                throw new BaseApiException('Method Not Allowed', $e->getCode(), $e->getStatusCode());
            }

            return error($e);
        } catch (BaseApiException $e) {
            return error($e);
        }
    }
}
