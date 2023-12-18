<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lessons';
    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'duration_minutes',
        'teacher_id',
        'subject_id',
        'location_id',
        'work_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id')->withTrashed();
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id')->withTrashed();
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_lessons', 'lesson_id', 'group_id')->orderBy('title')->withTrashed();
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id')->withTrashed();
    }

    public function work()
    {
        return $this->belongsTo(Work::class)->withTrashed();
    }

    public function students_visits()
    {
        return $this->belongsToMany(User::class, 'user_visits', 'lesson_id', 'user_id')
            ->withPivot('lesson_id', 'group_id', 'visited', 'comment');
    }

    public function students_grades()
    {
        return $this->belongsToMany(User::class, 'grade_users', 'lesson_id', 'user_id')
            ->withPivot('lesson_id', 'group_id', 'work_id', 'grade_id', 'date', 'comment');
    }

    public function scopeWithFilters($query, $lessons_start, $lessons_end, $groups, $teachers, $locations)
    {
        return $query->when($lessons_start && $lessons_end, function ($query) use ($lessons_start, $lessons_end) {
            $query->whereDate('start', '>=', $lessons_start)
            ->whereDate('end', '<', $lessons_end);
        })
            ->when(count($groups), function ($query) use ($groups) {
                $query->whereHas('groups', function ($query) use ($groups) {
                    $query->whereIn('group_id', $groups);
                });
            })
            ->when(count($teachers), function ($query) use ($teachers) {
                $query->whereHas('teacher', function ($query) use ($teachers) {
                    $query->whereIn('teacher_id', $teachers);
                });
            })
            ->when(count($locations), function ($query) use ($locations) {
                $query->whereHas('location', function ($query) use ($locations) {
                    $query->whereIn('location_id', $locations);
                });
            });
    }
}
