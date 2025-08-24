<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isVendor()) {
            return redirect()->route('home')->with('error', 'Access denied. Vendor account required.');
        }

        // Check if vendor profile exists and is approved
        $vendor = Auth::user()->vendor;
        if (!$vendor || $vendor->status !== 'approved') {
            return redirect()->route('home')->with('error', 'Your vendor account is not yet approved.');
        }

        return $next($request);
    }
}
