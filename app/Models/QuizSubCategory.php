<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSubCategory extends Model
{
    use HasFactory;

    protected $table = 'quizsubcategory';
    protected $primaryKey = 'id_quizsubCategory';

    protected $fillable = [
        'title',
        'image',
        'id_quizCategory',
        'id_Admin',
    ];

    public $timestamps = false;
}
