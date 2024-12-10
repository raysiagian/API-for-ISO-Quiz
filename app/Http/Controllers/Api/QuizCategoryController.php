<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuizCategory;
use Illuminate\Support\Facades\Storage;

class QuizCategoryController extends Controller
{
    //

    public function index()
    {
        $category = QuizCategory::all();

        // image in category
        // personal notes:
        // category = materi pada math gasing
        $category->transform(function ($item){
            $item ->image = url($item->image);
            return $item;
        });

        return response()->json(['data' => $category]);
    }

    public function show($id)
    {
        $category = QuizCategory::find($id);

        // Jika category ditemukan, kembalikan sebagai respons JSON
        if ($category) {
            return response()->json(['data' => $category]);
        }

        // Jika category tidak ditemukan, kembalikan pesan error
        return response()->json(['message' => 'Category not found'], 404);
    }


    public function store(Request $request){
        $request->validate([
            "title" => "required",
            "image" => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
            'id_Admin' => "required",
        ]);

        $imagePath = $request->file('image')->store('public/images');

        $category = QuizCategory::create([
            "title" => $request->title,
            "image"=> Storage::url($imagePath),
            "id_Admin" => $request->id_Admin,
        ]);
        return response()->json(['message' => 'Category created successfully', 'data' => $category], 200);
    }

    public function update(Request $request, string $id){
        $request->validate([
            "title" => "required",
            "image" => "required|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        $category = QuizCategory::find($id);

        if($category){
            $category->title = $request->title;

            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('public/images');
                $category->image = $image;
            }

            $category->save();

            return response()->json(['message' => 'category updated successfully', 'data' => $category], 200);

        }

        return response()->json(['message' => 'category failed to updated'], 404);
    }

    public function destroy(string $id){
        $category = QuizCategory::find($id);

        if($category){
            $category->delete();

            return response()->json(['message' => 'category deleted successfully']);
        }

        return response()->json(['message' => 'category failed to destroy']);
    }
}
