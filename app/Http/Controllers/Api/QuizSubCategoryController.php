<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizSubCategoryController extends Controller
{
    //

    public function index()
    {
        // Mengambil semua data subcategory
        $subcategory = QuizSubCategory::all();

        // Mengembalikan data unit sebagai respons JSON
        return response()->json(['data' => $subcategory]);
    }

    // menampilkan data subcategory beradasarkan id subcategory
    public function subcategorybyIdCategory(Request $request)
    {
        $id_quizCategory = $request->query('id_quizCategory');

        $query = QuizSubCategory::query();

        if($id_quizCategory){
            $query->where('id_quizCategory', $id_quizCategory);
            
        }

        $subcategory = $query->get();

        return response()->json(['data' => $subcategory]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_quizCategory' => 'required|exists:quizcategory,id_quizCategory',
            'id_Admin' => 'required',
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('public/images');

        $subcategory = QuizSubCategory::create([
            "title" => $request->title,
            "image"=> Storage::url($imagePath),
            "id_Admin" => $request->id_Admin,
            "id_quizCategory" => $request->id_quizCategory,
        ]);

        return response()->json(['message' => 'subcategory created successfully', 'data' => $subcategory], 200);
    }

    public function update(Request $request, string $id){
        $request->validate([
            "title" => "required",
            "image" => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $subcategory = QuizSubCategory::find($id);

        if($subcategory){
            $subcategory->title = $request->title;

            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('public/images');
                $subcategory->image = $image;
            }

            $subcategory->save();

            return response()->json(['message' => 'subcategory updated successfully', 'data' => $subcategory], 200);

        }

        return response()->json(['message' => 'subcategory failed to updated'], 404);
    }

    public function destroy(string $id){
        $subcategory = QuizSubCategory::find($id);

        if($subcategory){
            $subcategory->delete();

            return response()->json(['message' => 'subcategory deleted successfully']);
        }

        return response()->json(['message' => 'subcategory failed to destroy']);
    }

}
