<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\URL;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        if ($exception instanceof CustomException) {
            //
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
//        $previous = URL::previous();
//        if ($exception instanceof ModelNotFoundException)
//        {
//            return redirect($previous);
//        }
//
//
//        if ($exception instanceof \ErrorException) {
//            return redirect($previous);
//        }
//        else {
//            return parent::render($request, $exception);
//        }
        return parent::render($request, $exception);
    }
}
