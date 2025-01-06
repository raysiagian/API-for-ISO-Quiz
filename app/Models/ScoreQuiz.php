<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreQuiz extends Model
{
    use HasFactory;

    protected $table = 'scorequiz';
    protected $primaryKey = 'id_scoreQuiz';

    protected $fillable = [
        'id_User',
        'id_quizsubCategory',
        'id_quizCategory',
        'score_Quiz',
    ];


    public $timestamps = true;

    const CREATED_AT = 'created_At';
    const UPDATED_AT = 'updated_At';
}


				
