<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if (method_exists($exception, 'render')) {
            return $exception->render($request);
        } else if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        } else if ($exception instanceof AuthenticationException) {
            return response()->errorUnauthorized('登陆失效，请重新登录');
        } elseif ($exception instanceof NotFoundHttpException) {
            return response()->errorNotFound('地址错误');
        } elseif ($exception instanceof ThrottleRequestsException) {
            return response()->errorInternal('请求过于频繁，请稍后重试', 400);
        }

        if (config('app.debug') && (app()->environment() !== 'production')) {
            return response()->error(500, $exception->getMessage(), collect($exception->getTrace())->take(5));
        }

        return response()->errorInternal();
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->error($exception->status, 'The given data was invalid', $exception->errors());
    }
}
