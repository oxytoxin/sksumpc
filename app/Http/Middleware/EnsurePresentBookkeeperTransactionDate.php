<?php

namespace App\Http\Middleware;

use App\Models\TransactionDateHistory;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePresentBookkeeperTransactionDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            config(['app.transaction_date' => TransactionDateHistory::current_date()]);
        } catch (\Exception $e) {
        }

        return $next($request);
    }
}
