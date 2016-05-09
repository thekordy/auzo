<?php

namespace Kordy\Auzo\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuzoMiddleware
{

    /**
     * Check user authorization
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $ability
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $ability = null)
    {
        if (Auth::check() && $request->user()->can($ability ?: $this->requestedAbility($request), $request)) {
            return $next($request);
        }
        // TODO make abort 403 action configurable
        abort(403);
    }

    /**
     * return route name or controller{@}action (Path\Controller{@}method) if route name is not present
     *
     * @param Request $request
     * @return string
     */
    protected function requestedAbility(Request $request)
    {
        // TODO make sure action path works with paths like \Kordy\Auzo\Controllers\SomeController@method
        $action_path = explode('\\', $request->route()->getActionName());
        return $request->route()->getName() ?: end($action_path);
    }
}
