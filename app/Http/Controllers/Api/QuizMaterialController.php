<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuizMaterial;

class QuizMaterialController extends Controller
{
    // personal notes:
    // material = material video pada math gasing

    public function index()
    {
        $quizmaterial = QuizMaterial::all();

        return response()->json(['data' => $quizmaterial]);
    }

    public function show($id)
    {
        $quizmaterial = QuizMaterial::find($id);

        // Jika quizmaterial ditemukan, kembalikan sebagai respons JSON
        if ($quizmaterial) {
            return response()->json(['data' => $quizmaterial]);
        }

        // Jika q$quizmaterial tidak ditemukan, kembalikan pesan error
        return response()->json(['message' => 'Quiz Material not found'], 404);
    }


    // public function materialbyIdSubCategory(Request $request)
    // {
    //     $id_quizSubCategory = $request->input('id_quizSubCategory'); // Ambil parameter
    //     $query = QuizMaterial::query();
    
    //     if ($id_quizSubCategory) {
    //         $query->where('id_quizSubCategory', $id_quizSubCategory); // Filter berdasarkan subkategori
    //     }
    
    //     $materials = $query->get();
    
    //     return response()->json(['data' => $materials]);
    // }
    
    public function materialbyIdSubCategory(Request $request)
    {
        $id_quizSubCategory = $request->input('id_quizSubCategory');
        
        if (!$id_quizSubCategory) {
            return response()->json(['message' => 'id_quizSubCategory parameter is required'], 400);
        }

        $materials = QuizMaterial::where('id_quizSubCategory', $id_quizSubCategory)->get();

        return response()->json(['data' => $materials]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_quizSubCategory' => 'required|exists:quizsubcategory,id_quizSubCategory',
            'title' => 'required',
            'data' => 'required',
            'id_Admin' => 'required',
        ]);

        $quizmaterial = QuizMaterial::create([
            "id_quizSubCategory" => $request -> id_quizSubCategory,
            "title" => $request -> title,
            "data" => $request -> data,
            "id_Admin" => $request -> id_Admin,
        ]);

        return response()->json(['message' => 'quiz material created successfully', 'data' => $quizmaterial], 200);
    }

    public function update(Request $request, string $id){
        $request->validate([
            'title' => 'required',
            'data' => 'required',
        ]);

        $quizmaterial = QuizMaterial::find($id);

        if($quizmaterial){
            $quizmaterial->title = $request->title;

            $quizmaterial->data = $request->data;

            $quizmaterial->save();

            return response()->json(['message' => 'quiz material updated successfully', 'data' => $quizmaterial], 200);

        }

        return response()->json(['message' => 'quiz material failed to updated'], 404);
    }

    public function destroy(string $id){
        $quizmaterial = QuizMaterial::find($id);

        if($quizmaterial){
            $quizmaterial->delete();

            return response()->json(['message' => 'quiz material deleted successfully']);
        }

        return response()->json(['message' => 'quiz material failed to destroy']);
    }

}