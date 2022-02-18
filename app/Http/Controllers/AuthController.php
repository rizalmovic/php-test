<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function register(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ];

        try {
            $this->validate($request, $rules);
            $user = User::create($request->all());

            if (!$user) {
                return [
                    'status' => false,
                    'message' => 'Failed on registering user.'
                ];
            }
        } catch (ValidationException $err) {
            return [
                'status' => false,
                'message' => $err->errors()
            ];
        } catch (Exception $err) {
            Log::error(sprintf('Error on registering user.', $err->getMessage()));
            return [
                'status' => false,
                'message' => 'Failed on registering user.'
            ];
        }
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];

        try {
            $this->validate($request, $rules);
            $token = Auth::attempt($request->only(['email', 'password']));

            if (!$token) {
                return [
                    'status' => false,
                    'message' => 'Unathorized'
                ];
            }

            return [
                'status' => true,
                'access' => [
                    'token' => $token,
                    'type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60 * 24
                ]
            ];
        } catch (ValidationException $err) {
            return [
                'status' => false,
                'message' => $err->errors()
            ];
        } catch (Exception $err) {
            return [
                'status' => false,
                'message' => 'Failed on registering user.'
            ];
        }
    }
}
