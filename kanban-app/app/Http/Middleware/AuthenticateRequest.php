<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthenticateRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $isValid = false;
        // check if access token has provided
        if ($token = $request->bearerToken()) {
            DB::enableQueryLog();
            $user_id =
                DB::table('personal_access_tokens')
                ->where('token', $token)
                ->where('expires_at', '>', date('Y-m-d h:i:s'))
                ->value('tokenable_id');

            if ($user_id) {
                $request['user'] = User::find($user_id);
                $isValid = true;
            }
        }

        if (!$isValid) {
            return response()->json([
                'success'   => false,
                'message' => 'No Access Token Provided',
                'data' => []
            ]);
        }

        return $next($request);
    }
}
