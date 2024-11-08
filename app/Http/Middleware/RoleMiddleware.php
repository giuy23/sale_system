<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, ...$roles): Response
  {
    if (Auth::check() && in_array(Auth::user()->role_id, haystack: [3])) {
      return redirect(route('client.view', absolute: false));
    }
    if (Auth::check() && in_array(Auth::user()->role_id, $roles)) {
      return $next($request);
    }
    return back();
    // return Inertia::location(env('URL_PAGE_CLIENT'));
  }
}
