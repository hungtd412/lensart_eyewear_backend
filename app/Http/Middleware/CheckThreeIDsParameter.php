<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckThreeIDsParameter {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $id1 = $request->route()->parameter('id1');
        $id2 = $request->route()->parameter('id2');
        $id3 = $request->route()->parameter('id3');
        if (!$id1 || !$id2 || !$id3) {
            return response()->json([
                'message' => 'Missing id parameters'
            ], 400);
        }

        if (!is_numeric($id1) || !is_numeric($id2) || !is_numeric($id3)) {
            return response()->json([
                'message' => 'ID must be a number'
            ], 400);
        }

        return $next($request);
    }
}
