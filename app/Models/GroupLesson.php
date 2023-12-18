<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupLesson extends Model
{
    use HasFactory;

    protected $table = 'group_lessons';
    protected $guarded = false;
    public $timestamps = false;
}
