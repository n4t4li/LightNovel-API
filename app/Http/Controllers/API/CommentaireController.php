<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Commentaire;

class CommentaireController extends BaseController
{
    public function store(Request $request, $id)
{
    $validated = $request->validate([
        'texte' => 'required|string|max:1000',
    ]);

    $validated['user_id'] = \Auth::id();
    $validated['light_novel_id'] = $id;

    $comment = Commentaire::create($validated);

    return response()->json([
        'success' => true,
        'data' => $comment
    ]);
}

}