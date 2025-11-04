<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends BaseController
{
//pour register 
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $token = $user->createToken('LightNovelAPI')->plainTextToken;

        return $this->sendResponse([
            'user'  => $user,
            'token' => $token,
        ], 'Inscription réussie.');
    }

   // pour login et logout
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->sendError('Non autorisé', ['error' => 'Email ou mot de passe incorrect'], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('LightNovelAPI')->plainTextToken;

        return $this->sendResponse([
            'user'  => $user,
            'token' => $token,
        ], 'Connexion réussie.');
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendResponse([], 'Déconnexion réussie.');
    }
}
