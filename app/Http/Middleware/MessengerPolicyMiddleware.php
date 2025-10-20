<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Stub policy middleware for Messenger 24h window & tag checks.
 * Hook this only to send-message routes or job dispatch points.
 */
class MessengerPolicyMiddleware {
    public function handle(Request $request, Closure $next) {
        // Example checks (pseudo):
        // - $request->input('recipient_id')
        // - ensure last_user_interaction_ts within 24h OR message_tag provided & allowed.
        // In this patch we only demonstrate structure; implement your own lookup.
        return $next($request);
    }
}
