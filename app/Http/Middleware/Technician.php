<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Technician
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'technician') {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized. Technician access required.',
            ], 403);
        }

        return $next($request);
    }
}
