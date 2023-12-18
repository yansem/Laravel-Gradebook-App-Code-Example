<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'groups';
    protected $fillable = ['title', 'description', 'date_start', 'date_end'];

    public function students()
    {
        return $this->belongsToMany(User::class, 'group_users', 'group_id', 'user_id')->orderBy('last_name');
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'group_lessons', 'group_id', 'lesson_id');
    }

    public function scopeWithFilters($query, $id, $title, $description, $group_start, $group_end, $trashed)
    {
        return $query->when($id, function ($query) use ($id) {
            $query->where('id', $id);
        })
            ->when($title, function ($query) use ($title) {
                $query->where('title', 'LIKE', '%'.$title.'%');
            })
            ->when($description, function ($query) use ($description) {
                $query->where('description', 'LIKE', '%'.$description.'%');
            })
            ->when($group_start, function ($query) use ($group_start) {
                $query->whereDate('date_start', '>=', $group_start);
            })
            ->when($group_end, function ($query) use ($group_end) {
                $query->whereDate('date_end', '<=', $group_end);
            })
            ->when($trashed === 'with', function ($query) use ($trashed) {
                $query->withTrashed();
            })
            ->when($trashed === 'only', function ($query) use ($trashed) {
                $query->onlyTrashed();
            });
    }
}
