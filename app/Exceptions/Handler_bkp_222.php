<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use DB;
use Redirect;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
        $requestURL = $request->url();
        $ckRedx = DB::table('redirection')->where('type', '=', '301')->where('status', '=', '1')
        ->where('source_url', '=', $requestURL)->get();
        //echo $requestURL;
        //dd($ckRedx);
        
        if( !empty($ckRed) && $ckRed->destination_url != '' ) {
            die('1.1');
            return Redirect::to($ckRed->destination_url, 301)->send(); 
        }
        
        if ($this->isHttpException($exception)) {

            if ($exception->getStatusCode() == 404) {

                $requestURL = $request->url();
                $ckRed = DB::table('redirection')->where('type', '=', '301')->where('status', '=', '1')
                ->where('source_url', '=', $requestURL)->first();
                //echo $requestURL;
                //die('2');
                if( !empty($ckRed) && $ckRed->destination_url != '' ) {
                    
                    return Redirect::to($ckRed->destination_url, 301)->send(); 
                }

                $data = DB::table('redirection')->where('type', '=', '404')->first();
                if( !empty($data) && $data->source_url != '' ) {

                    return Redirect::to($data->source_url);
                }

                return redirect()->route('notfound', array('lng' => 'en'));
                //return response()->view('errors.404');
            }
             
            if ($exception->getStatusCode() == 500) {
                return response()->view('errors.505');
            }
        }

        return parent::render($request, $exception);
    }
}
