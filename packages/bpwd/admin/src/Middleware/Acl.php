<?php namespace Bpwd\Admin\Middleware;

use Closure;

class Acl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null)
    {

        if (!app('Illuminate\Contracts\Auth\Guard')->guest()) {

            //dd($request->user());

            //die(get_class($request->user()));
            //$request->user()->hasRole();
            //die();
            //dd($request->user());
            if ($request->user()->can($permission)) {
                return $next($request);
            }
        }

        return $request->ajax ? response('Unauthorized.', 401) : redirect('admin/logout');
    }
}
