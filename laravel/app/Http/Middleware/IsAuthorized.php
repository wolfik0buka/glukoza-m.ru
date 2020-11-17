<?php namespace App\Http\Middleware;

use Closure;
use Session;

class IsAuthorized
{

    public function __construct(){}


    public function handle($request, Closure $next)
    {
        if (Session::has('user.id'))
        {
            return $next($request);
        }

        return redirect()->to('cabinet/auth_form');
    }

}
