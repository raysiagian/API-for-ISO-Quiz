<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizMaterial extends Model
{
    use HasFactory;

    protected $table = 'quizmaterial';
    protected $primaryKey = 'id_quizMaterial';

    protected $fillable = [
        'id_quizCategory',
        'title',
        'data',
        'id_Admin',
    ];
}
