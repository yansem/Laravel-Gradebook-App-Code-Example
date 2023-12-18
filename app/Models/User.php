<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'patronymic',
        'login',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_users', 'user_id', 'group_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'teacher_id', 'id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->last_name} {$this->first_name} {$this->patronymic} ";
    }

    public function getLastNameInitialsAttribute(): string
    {
        $first_name_char = mb_strtoupper(mb_substr($this->first_name, 0, 1));
        $patronymic_char = mb_strtoupper(mb_substr($this->patronymic, 0, 1));

        return "{$this->last_name} $first_name_char.$patronymic_char.";
    }

    public function isAdmin()
    {
        return $this->role_id === 1;
    }

    public function isTeacher()
    {
        return $this->role_id === 2;
    }

    public function isStudent()
    {
        return $this->role_id === 3;
    }

    public function scopeOnlyTeachers($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereTitle('Инструктор');
        });
    }

    public function scopeOnlyStudents($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->whereTitle('Студент');
        });
    }

    public function scopeWithFilters($query, $id, $last_name, $first_name, $patronymic, $login, $role, $trashed)
    {
        return $query->when($id, function ($query) use ($id) {
            $query->where('id', $id);
        })
            ->when($last_name, function ($query) use ($last_name) {
                $query->where('last_name', 'LIKE', '%'.$last_name.'%');
            })
            ->when($first_name, function ($query) use ($first_name) {
                $query->where('first_name', 'LIKE', '%'.$first_name.'%');
            })
            ->when($patronymic, function ($query) use ($patronymic) {
                $query->where('patronymic', 'LIKE', '%'.$patronymic.'%');
            })
            ->when($login, function ($query) use ($login) {
                $query->where('login', 'LIKE', '%'.$login.'%');
            })
            ->when($role, function ($query) use ($role) {
                $query->whereHas('role', function ($q) use ($role) {
                    $q->whereTitle($role);
                });
            })
            ->when($trashed === 'with', function ($query) use ($trashed) {
                $query->withTrashed();
            })
            ->when($trashed === 'only', function ($query) use ($trashed) {
                $query->onlyTrashed();
            });
    }
}
