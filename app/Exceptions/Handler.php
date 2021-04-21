<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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

    public function render($request, Throwable $e)
    {
        if($e instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($e,$request);
        }

        if($e instanceof ModelNotFoundException){
            $modelo =  strtolower(class_basename($e->getModel())) ;
            return $this->errorReponse('No Existe ninguna instancia '.$modelo.' con el id especificado',404);
        }
        if ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        }
        if($e instanceof AuthorizationException){
            return $this->errorReponse('No Posee permisos para ejecutar esta acción',403);
        }
        if($e instanceof NotFoundHttpException){
            return $this->errorReponse('No se encontro la URL especificada ',404);
        }
        if($e instanceof MethodNotAllowedHttpException){
            return $this->errorReponse('El método especificado en la petición no es válido',405);
        }
        if($e instanceof HttpException){
            return $this->errorReponse($e->getMessage(),$e->getStatusCode());
        }
        if($e instanceof QueryException){
            $codigo = $e->errorInfo[1];
            if($codigo == 1451){
                return $this->errorReponse('No se puede eliminar de forma permanente el recurso porque esta relacionado con algun otro',409);
            }
        }
        if(config('app.debug')){
            return parent::render($request,$e);
        }
        return $this->errorReponse('Falla inesperada intente de luego',500);

    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $error = $e->validator->errors()->getMessages();
        return $this->errorReponse($error,422);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorReponse('No autenticado.',401);
    }

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
}
