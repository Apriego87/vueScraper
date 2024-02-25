<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        // Create the user
        User::create($userData);

        return response()->json([
            'status' => true,
            'msg' => 'Usuario creado correctamente!'
        ]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (
            Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ])
        ) {
            $user = Auth::user();

            $token = $user->createToken('myToken')->accessToken;

            return response()->json([
                'status' => true,
                'msg' => 'Inicio de sesión correcto',
                'token' => $token
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'Datos de inicio de sesión incorrectos.'
            ]);
        }
    }
    public function profile()
    {
        $user = Auth::user();

        return response()->json([
            'status' => true,
            'msg' => 'Información del perfil',
            'data' => $user
        ]);
    }
    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'msg' => 'Sesión cerrada correctamente'
        ]);
    }
}
