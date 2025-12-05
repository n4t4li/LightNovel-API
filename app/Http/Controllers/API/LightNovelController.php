<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\LightNovel;
use Illuminate\Support\Facades\Validator;

class LightNovelController extends BaseController
{
    //Afficher tous les Light Novels (GET /api/lightnovels)
    public function index()
    {
        $novels = LightNovel::all();
        return $this->sendResponse($novels, 'Liste des Light Novels récupérée avec succès.');
    }

    //Créer un nouveau Light Novel (POST /api/lightnovels)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'statut' => 'nullable|string|max:255',
            'chapitres' => 'nullable|integer',
            'Contenu' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erreur de validation.', $validator->errors(), 400);
        }

        $input = $request->all();

        // pour gérer l'upload d'image
        if ($request->hasFile('photo')) {
            $imageName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(public_path('images'), $imageName);
            $input['photo'] = $imageName;
        }

        $novel = LightNovel::create($input);

        return $this->sendResponse($novel, 'Light Novel créé avec succès.', 201);
    }

    //Afficher un Light Novel spécifique (GET /api/lightnovels/{id})
    public function show($id)
{
    $novel = LightNovel::with(['commentaires.user'])->find($id);

    if (!$novel) {
        return response()->json(['success' => false, 'message' => 'Light novel non trouvé'], 404);
    }

    return response()->json(['success' => true, 'data' => $novel]);
}


    //Mettre à jour un Light Novel (PUT /api/lightnovels/{id})
    public function update(Request $request, $id)
    {
        $novel = LightNovel::find($id);

        if (!$novel) {
            return $this->sendError('Light Novel non trouvé.', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|required|string|max:255',
            'auteur' => 'sometimes|required|string|max:255',
            'statut' => 'nullable|string|max:255',
            'chapitres' => 'nullable|integer',
            'Contenu' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erreur de validation.', $validator->errors(), 400);
        }

        $input = $request->all();

        // pour replacer les images
        if ($request->hasFile('photo')) {
            $imageName = time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(public_path('images'), $imageName);
            $input['photo'] = $imageName;
        }

        $novel->update($input);

        return $this->sendResponse($novel, 'Light Novel mis à jour avec succès.');
    }

    //Supprimer un Light Novel (DELETE /api/lightnovels/{id})
    public function destroy($id)
    {
        $novel = LightNovel::find($id);

        if (!$novel) {
            return $this->sendError('Light Novel non trouvé.');
        }

        $novel->delete();
        return $this->sendResponse([], 'Light Novel supprimé avec succès.');
    }
}
