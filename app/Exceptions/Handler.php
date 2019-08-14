<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Traits\ApiResponser;
use App\Exceptions\Handler;
use Illuminate\Database\QueryException;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof ModelNotFoundException){
            //return response()->json(['error'=>"No existe ninguna instancia con el Id expecificado", 'code'=> 404], 404);
            $modelo = class_basename($exception->getModel());
            return $this->errorResponse("No existe ningun registro de { $modelo } con el Id expecificado",404);
        }
        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof AuthorizationException){
            return $this->errorResponse("No tiene permisos para ejecutar esta accion ",403);
        }
        
        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse("URL no encontrada ",404);
        }

        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if($exception instanceof QueryException){
            $codigo = $exception->errorInfo[1];
            if($codigo == 1451){
                return $this->errorResponse("No  se puede eliminar ya que tiene relacion con otras tablas",409);
            }
            
        }
        if(config('app.debug')){
            
            return parent::render($request, $exception);
        }
        return $this->errorResponse('Falla inesperada',500);
        
        
        
    }
}
