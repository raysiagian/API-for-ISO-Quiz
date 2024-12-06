<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuizMaterial;

class QuizMaterialController extends Controller
{
    // personal notes:
    // material = unit pada math gasing
}




// public function getUnitBonusByMateri($id_materi)
// {
//     // Mengambil semua unit bonus berdasarkan id materi
//     $unitBonuss = UnitBonus::where('id_materi', $id_materi)->get();

//     // Mengembalikan data unit bonus sebagai respons JSON
//     return response()->json(['data' => $unitBonuss]);
// }

// Route::get('/getByMateri/{id_materi}', [UnitBonusController::class, 'getUnitBonusByMateri']);