<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizCategory extends Model
{
    use HasFactory;

    protected $table = 'quizcategory';
    protected $primaryKey = 'id_quizCategory';

    protected $fillable = [
        'title',
        'image',
        'id_Admin',
    ];

    public $timestamps = false;
}

