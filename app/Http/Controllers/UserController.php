<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    // Registro
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return response()->json([
            'message' => 'Usuario registrado y autenticado',
            'user' => $user // 👈 descomenta esto si lo necesitas también
        ]);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Login exitoso',
                'user' => Auth::user() // ✅ este es el fix importante
            ]);
        }

        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Sesión cerrada']);
    }

    // Usuario autenticado
    public function me()
    {
        return response()->json(Auth::user());
    }
}
