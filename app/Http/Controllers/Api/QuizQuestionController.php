<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizQuestionController extends Controller
{
    //

    public function index()
    {
        // Mengambil semua data pertanyaan pretest
        $question = QuizQuestion::all();

        // Mengembalikan data pertanyaan pretest sebagai respons JSON
        return response()->json(['data' => $question]);
    }

    public function questionbyIdSubCategory(Request $request)
    {
        $id_quizSubCategory = $request->query('id_quizSubCategory');

        $query = QuizQuestion::query();

        if ($id_quizSubCategory) {
            $query->where('id_quizSubCategory', $id_quizSubCategory);
        }

        $question = $query->get();

        // Mengembalikan data pertanyaan posttest sebagai respons JSON
        return response()->json(['data' => $question]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_quizSubCategory' => 'required|exists:quizsubcategory,id_quizSubCategory',
            'question' => 'required|string',
            'option_A' => 'required|string',
            'option_B' => 'required|string',
            'option_C' => 'required|string',
            'option_D' => 'required|string',
            'option_E' => 'required|string',
            'correct_Answer' => 'required|string',
            'id_Admin' => 'required',
        ]);

        // Membuat record baru dalam database
        $question = QuizQuestion::create($request->all());

        // Mengembalikan pertanyaan pretest yang baru dibuat sebagai respons JSON
        return response()->json(['message' => 'Question created successfully', 'data' => $question], 201);
    }

    public function show($id)
    {

        $question = QuizQuestion::find($id);

        if ($question) {
            return response()->json(['data' => $question]);
        }
        return response()->json(['message' => 'Question not found'], 404);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'question' => 'required|string',
            'option_A' => 'required|string',
            'option_B' => 'required|string',
            'option_C' => 'required|string',
            'option_D' => 'required|string',
            'option_E' => 'required|string',
            'correct_Answer' => 'required|string',
        ]);

        // Temukan pertanyaan pretest berdasarkan ID
        $question = QuizQuestion::find($id);

        // Jika pertanyaan pretest ditemukan, update dengan data baru
        if ($question) {
            $question->update($request->all());
            return response()->json(['message' => 'Question updated successfully', 'data' => $question], 200);
        }

        // Jika pertanyaan pretest tidak ditemukan, kembalikan pesan error
        return response()->json(['message' => 'Question not found'], 404);
    }

    public function destroy($id)
    {
        $question = QuizQuestion::find($id);
        if ($question) {
            $question->delete();

            return response()->json(['message' => 'Question deleted successfully']);
        }

        return response()->json(['message' => 'Question not found'], 404);
    }


    // public function getQuestionByID($id_posttest)
    // {
    //     $posttest = QuestionPosttest::where('id_posttest', $id_posttest)->get();
    //     return response()->json(['data' => $posttest]);
    // }


}
