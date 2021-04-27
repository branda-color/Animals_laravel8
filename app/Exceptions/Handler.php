<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use Illuminate\Http\Response;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Illuminate\Auth\AuthenticationException;

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

            //scope驗證失敗

            if ($exception instanceof AuthorizationException) {
                return $this->errorResponse(
                    $exception->getMessage(),
                    Response::HTTP_FORBIDDEN //403用戶端並無訪問權限，例如未被授權，所以伺服器拒絕給予應有的回應
                );
            }
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
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        //客戶端請求json格式
        if ($request->expectsJson()) {
            return $this->errorResponse(
                $exception->getMessage(),
                Response::HTTP_UNAUTHORIZED //401需要授權以回應請求
            );
        } else {
            //客戶端非請求json格式轉回登入畫面
            return redirect()->guest($exception->redirectTo() ?? route('login'));
        }
    }
}
