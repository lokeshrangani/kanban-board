<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success'   => false,
                'message' => 'Unauthorized',
                'data' => []
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('personal_access_token');
        $token = $tokenResult->accessToken;
        $token->expires_at = Carbon::now()->addHour(12);
        $token->save();

        return response()->json([
            'success'   => true,
            'message'   => 'LoggedIn Successfully',
            'data' => [
                'user' => Auth::user()->name,
                'access_token' => $token->token,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
            ]
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'success'   => true,
            'message'   => 'Successfully created user',
            'data'      => ['id' => $user->id]
        ], 201);
    }

    public function logout(Request $request)
    {
        // expire token
        DB::table('personal_access_tokens')->where('token', $request->bearerToken())->update([
            'expires_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json([
            'success'   => true,
            'message' => 'Successfully logged out',
            'data'      => []
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->all()['user'];

        return response()->json([
            'success'   => true,
            'message'   => 'Successfully user fetched',
            'data'      => ['user' => $user]
        ], 201);
    }
}
