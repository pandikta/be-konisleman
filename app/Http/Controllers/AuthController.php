<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //Validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        //Request is valid, create new user
        $user = User::create([
            'id_user' => Uuid::uuid4()->getHex(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        } else {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'success'   => false,
                    'message' => ['Email not registered.']
                ], 422);
            } else if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success'   => false,
                    'message' => ['Wrong password.']
                ], 422);
            } elseif ($user->is_active != 1) {
                return response()->json([
                    'success'   => false,
                    'message' => ['Account not active.']
                ], 422);
            }
        }

        if (!$token = auth()->attempt($validator->validate())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // create token
        return $this->responeWithToken($token);
    }

    public function responeWithToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => 'Sucessfully Login',
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 24 * 60, //1 hari
        ]);
    }

    //cek token
    public function me(Request $request)
    {
        $name = $request->query('name');
        return
            $name;
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'User succesfull Logout',
        ]);
    }
}
