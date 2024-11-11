<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTypeParameter {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $id = $request->route()->parameter('type');
        if (!$id) {
            return response()->json([
                'message' => 'Missing type parameter'
            ], 400);
        }

        if (!is_numeric($id)) {
            return response()->json([
                'message' => 'Type must be a number'
            ], 400);
        }

        if ($id == 1) {
            return response()->json([
                'message' => 'Invalid type'
            ], 400);
        }

        return $next($request);
    }
}
