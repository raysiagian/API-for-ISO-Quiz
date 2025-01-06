<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScoreQuiz;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScoreQuizController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter id_user dan id_quizsubCategory dari request
        $id_user = $request->input('id_user');
        $id_quizsubCategory = $request->input('id_quizsubCategory');

        // Query untuk memfilter data berdasarkan id_user dan id_quizsubCategory
        $scoreQuiz = ScoreQuiz::where('id_User', $id_user)
            ->where('id_quizsubCategory', $id_quizsubCategory)
            ->get();

        // Return hasil dalam bentuk JSON
        return response()->json([
            'data' => $scoreQuiz
        ]);
    }


    public function sendScore(Request $request, $id_quizsubCategory)
    {
        // Validasi input data
        $request->validate([
            'id_quizCategory' => 'required|integer',
            'score_Quiz' => 'required|integer',
        ]);

        // Ambil ID pengguna yang sedang login
        $id_User = Auth::id();

        // Perbarui jika data sudah ada, tambahkan jika belum ada
        $scoreQuiz = ScoreQuiz::updateOrCreate(
            [
                'id_quizsubCategory' => $id_quizsubCategory,
                'id_User' => $id_User, // Pastikan ID pengguna sesuai dengan yang sedang login
            ],
            [
                'id_quizCategory' => $request->id_quizCategory,
                'score_Quiz' => $request->score_Quiz,
            ]
        );

        // Kembalikan data hasil setelah berhasil memperbarui atau membuat
        return response()->json([
            'message' => 'Score updated successfully',
            'data' => $scoreQuiz, // Menampilkan data hasil yang sudah diperbarui
        ], 200);
    }


        
    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'id_quizsubCategory' => 'required|integer',
            'id_quizCategory' => 'required|integer',
            'score_Quiz' => 'required|integer',
        ]);

        // Gunakan ID pengguna yang sedang login jika tidak diberikan
        $id_User = $request->input('id_User', Auth::id());

        // Cari data berdasarkan ID
        $scoreQuiz = ScoreQuiz::find($id);

        if (!$scoreQuiz) {
            return response()->json([
                'message' => 'Score not found',
            ], 404);
        }

        // Periksa apakah ID pengguna sesuai dengan data yang ingin diperbarui
        if ($scoreQuiz->id_User != $id_User) {
            return response()->json([
                'message' => 'Unauthorized to update this score',
            ], 403);
        }

        // Perbarui data
        try {
            $scoreQuiz->update([
                'id_User' => $id_User,
                'id_quizsubCategory' => $validatedData['id_quizsubCategory'],
                'id_quizCategory' => $validatedData['id_quizCategory'],
                'score_Quiz' => $validatedData['score_Quiz'],
            ]);

            return response()->json([
                'message' => 'Score updated successfully',
                'data' => $scoreQuiz,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update score',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    
    public function delete($id)
    {
        // Cari data berdasarkan id
        $scoreQuiz = ScoreQuiz::find($id);

        if (!$scoreQuiz) {
            return response()->json([
                'message' => 'Score not found'
            ], 404);
        }

        // Hapus data
        $scoreQuiz->delete();

        return response()->json([
            'message' => 'Score deleted successfully'
        ]);
    }

    public function showHistory($id_user)
    {
        // Ambil data berdasarkan id_user dan urutkan berdasarkan kolom updated_At
        $history = ScoreQuiz::where('id_User', $id_user)
            ->orderBy('updated_At', 'desc') // Urutkan berdasarkan waktu terakhir diperbarui
            ->get();
    
        if ($history->isEmpty()) {
            return response()->json([
                'message' => 'No history found for the user',
                'data' => []
            ], 404);
        }
    
        return response()->json([
            'message' => 'History retrieved successfully',
            'data' => $history
        ]);
    }
    
    public function totalScore($id_user)
    {
        // Menghitung total score berdasarkan id_user
        $totalScore = ScoreQuiz::where('id_User', $id_user)->sum('score_Quiz');

        // Mengembalikan response dengan total score
        return response()->json([
            'id_user' => $id_user,
            'total_score' => $totalScore,
        ]);
    }

    // public function highestScoreBySubCategory($id_user)
    // {
    //     // Query nilai tertinggi per subkategori untuk pengguna tertentu
    //     $highestScores = ScoreQuiz::where('id_User', $id_user)
    //         ->select('id_quizsubCategory', DB::raw('MAX(score_Quiz) as highest_score'))
    //         ->groupBy('id_quizsubCategory')
    //         ->orderBy('id_quizsubCategory')
    //         ->get();

    //     return response()->json([
    //         'data' => $highestScores
    //     ]);
    // }

    public function highestScoreBySubCategory()
    {
        // Ambil ID user dari token
        $id_user = auth()->id();

        // Query nilai tertinggi per subkategori untuk pengguna yang sedang login
        $highestScores = ScoreQuiz::where('id_User', $id_user)
            ->select('id_quizsubCategory', DB::raw('MAX(score_Quiz) as highest_score'))
            ->groupBy('id_quizsubCategory')
            ->orderBy('id_quizsubCategory')
            ->get();

        return response()->json([
            'data' => $highestScores
        ]);
    }


    public function leaderboard()
    {
        // Query untuk menghitung total score setiap user dan join dengan tabel user
        $leaderboard = ScoreQuiz::select('user.username', 'scorequiz.id_User')
            ->selectRaw('SUM(score_Quiz) as total_score')
            ->join('user', 'scorequiz.id_User', '=', 'user.id_User') // Join dengan tabel user, menggunakan 'user' sebagai nama tabel
            ->groupBy('scorequiz.id_User', 'user.username') // Kelompokkan berdasarkan id_User dan username
            ->orderByDesc('total_score') // Urutkan berdasarkan skor total
            ->limit(10) // Ambil 10 besar
            ->get();
    
        // Mengembalikan response JSON leaderboard
        return response()->json([
            'leaderboard' => $leaderboard
        ]);
    }
    
    public function userLeaderboard($userId)
    {
        // Menghitung total score dari setiap pengguna
        $userScore = ScoreQuiz::selectRaw('SUM(score_Quiz) as total_score')
            ->where('id_User', $userId)
            ->groupBy('id_User')
            ->first();

        if (!$userScore) {
            return response()->json([
                'message' => 'User not found or no scores available'
            ], 404);
        }

        // Mengambil semua user dan total skor mereka
        $leaderboard = ScoreQuiz::selectRaw('id_User, SUM(score_Quiz) as total_score')
            ->groupBy('id_User')
            ->orderByDesc('total_score')
            ->get();

        // Menentukan rank pengguna berdasarkan total skor
        $rank = $leaderboard->search(function ($item) use ($userId) {
            return $item->id_User == $userId;
        }) + 1; // Menambahkan 1 untuk rank (dimulai dari 1)

        // Mengambil data pengguna (username dan total score)
        $userData = User::select('username')
            ->where('id_User', $userId)
            ->first();

        if (!$userData) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Menggabungkan data pengguna dengan rank dan total score
        return response()->json([
            'username' => $userData->username,
            'rank' => $rank,
            'total_score' => $userScore->total_score,
        ]);
    }



}

