<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{

    protected $except = [
        "/webhooks/*"
    ];

	public function handle($request, Closure $next)
	{
		return parent::handle($request, $next);
	}

}
