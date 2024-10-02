<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Auth;
use Exception;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
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
        if ($exception instanceof \Exception && $exception->getMessage() === 'processing data') {
            return redirect()->route('admin.testinginfo');
        }
        if ($exception instanceof \Exception && $exception->getMessage() === 'error response') {
            return new Response('');
        }
        if ($exception instanceof \Exception && $exception->getMessage() === 'importingerror') {
            return redirect()->back()->with('error', 'You are selected different file, please select customer import file.');
        }
        return parent::render($request, $exception);
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson())
        {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if ($request->is('customer') || $request->is('customer/*'))
        {
            return redirect()->guest('/customer/login');
        }
        if ($request->is('admin') || $request->is('admin/*'))
        {
            return redirect()->guest('/admin/login');
        }

        return redirect()->guest(route('login'));
    }
}
