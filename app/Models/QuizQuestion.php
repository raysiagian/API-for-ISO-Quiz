<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $table = 'quizquestion';
    protected $primaryKey = 'id_quizQuestion';

    protected $fillable = [
        'id_quizSubCategory',
        'question',
        'option_A',
        'option_B',
        'option_C',
        'option_D',
        'option_E',
        'correct_Answer',
        'id_Admin',
    ];

    public $timestamps = false;
}
