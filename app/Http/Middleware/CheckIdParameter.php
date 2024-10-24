<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIdParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route()->parameter('id');
        if (!$id) {
            return response()->json([
                'message' => 'Missing id'
            ], 400);
        }

        if (!is_numeric($id)) {
            return response()->json([
                'message' => 'ID must be a number'
            ], 400);
        }

        return $next($request);
    }
}
