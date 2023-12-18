<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeUser extends Model
{
    use HasFactory;

    protected $table = 'grade_users';
    protected $guarded = false;
    public $timestamps = false;
}
