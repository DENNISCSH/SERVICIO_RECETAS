<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;

class RecipeController extends Controller
{
    // Listar todas las recetas
    public function index()
    {
        $recipes = Recipe::with('user', 'ingredients', 'steps', 'categories')->get();
        return response()->json($recipes);
    }

    // Ver una receta específica
    public function show($id)
    {
        $recipe = Recipe::with('user', 'ingredients', 'steps', 'categories')->find($id);

        if (!$recipe) {
            return response()->json(['message' => 'Receta no encontrada'], 404);
        }

        return response()->json($recipe);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string',
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'ingredients' => 'required|array',
                'steps' => 'required|array',
                'categories' => 'nullable|array',
            ]);

            $recipe = Recipe::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'description' => $request->description,
                'image' => $request->image,
            ]);

            foreach ($request->ingredients as $ingredient) {
                $recipe->ingredients()->create($ingredient);
            }

            foreach ($request->steps as $step) {
                $recipe->steps()->create($step);
            }

            if ($request->has('categories')) {
                $recipe->categories()->sync($request->categories);
            }

            return response()->json(['message' => 'Receta creada', 'recipe' => $recipe->load('ingredients', 'steps', 'categories')]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la receta',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Actualizar receta
    public function update(Request $request, $id)
    {
        $recipe = Recipe::where('user_id', $request->user_id)->find($id);

        if (!$recipe) {
            return response()->json(['message' => 'No autorizado o receta no encontrada'], 403);
        }

        $recipe->update($request->only('title', 'description', 'image'));

        // Opcional: reemplazar ingredientes/pasos
        if ($request->has('ingredients')) {
            $recipe->ingredients()->delete();
            foreach ($request->ingredients as $ingredient) {
                $recipe->ingredients()->create($ingredient);
            }
        }

        if ($request->has('steps')) {
            $recipe->steps()->delete();
            foreach ($request->steps as $step) {
                $recipe->steps()->create($step);
            }
        }

        if ($request->has('categories')) {
            $recipe->categories()->sync($request->categories);
        }

        return response()->json(['message' => 'Receta actualizada', 'recipe' => $recipe->load('ingredients', 'steps', 'categories')]);
    }

    // Eliminar receta
    public function destroy($id, Request $request)
    {
        $recipe = Recipe::where('user_id', $request->user_id)->find($id);

        if (!$recipe) {
            return response()->json(['message' => 'No autorizado o receta no encontrada'], 403);
        }

        $recipe->delete();
        return response()->json(['message' => 'Receta eliminada']);
    }
}
