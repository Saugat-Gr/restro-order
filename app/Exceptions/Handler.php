<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException && $exception->getStatusCode() === 403) {
            return redirect()->back() // or any safe route
                ->with('error', 'You do not have permission to perform this action.');
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return redirect()->route('welcome')
                ->with('error', 'Invalid HTTP method used for this route.');
        }

        return parent::render($request, $exception);
    }
}
