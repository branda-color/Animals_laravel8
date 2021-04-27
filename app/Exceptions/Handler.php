<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use Illuminate\Http\Response;
use App\Traits\ApiResponseTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        /*dd($exception); 可以中斷程式碼並傳入exception當下狀態*/
        if ($request->expectsJson()) { //攔截錯誤的程式碼
            //1.model找不到資源
            if ($exception instanceof ModelNotFoundException) {
                return $this->errorResponse(
                    '找不到資源',
                    Response::HTTP_NOT_FOUND
                );
            }
            //2.網址輸入錯誤(新增判斷)
            if ($exception instanceof NotFoundHttpException) {
                return $this->errorResponse(
                    '找不到網址',
                    Response::HTTP_NOT_FOUND
                );
            }
            //3.網址不允許該請求動詞
            if ($exception instanceof MethodNotAllowedException) {
                return $this->errorResponse(
                    $exception->getMessage(),
                    Response::HTTP_METHOD_NOT_ALLOWED
                );
            }
        }

        return parent::render($request, $exception);
    }
}
