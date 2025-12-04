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
            'g-recaptcha-response' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 422);
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => env('VITE_RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response')
        ]);

        $captcha = $response->json();
        if (empty($captcha['success']) || !$captcha['success']) {
            return response()->json(['success' => false, 'message' => 'Échec de la vérification reCAPTCHA'], 400);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['success' => true, 'message' => 'Utilisateur enregistré avec succès.', 'data' => [
            'token' => $user->createToken('LightNovelAPI')->plainTextToken,
            'name' => $user->name
        ]], 201);
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
