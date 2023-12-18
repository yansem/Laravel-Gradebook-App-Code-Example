<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grades';
    protected $guarded = false;
    public $timestamps = false;

    public function scopeWithFilters($query, $id, $title, $value, $description, $trashed)
    {
        return $query->when($id, function ($query) use ($id) {
            $query->where('id', $id);
        })
            ->when($title, function ($query) use ($title) {
                $query->where('title', 'LIKE', '%'.$title.'%');
            })
            ->when($value, function ($query) use ($value) {
                $query->where('value', $value);
            })
            ->when($description, function ($query) use ($description) {
                $query->where('description', 'LIKE', '%'.$description.'%');
            })
            ->when($trashed === 'with', function ($query) use ($trashed) {
                $query->withTrashed();
            })
            ->when($trashed === 'only', function ($query) use ($trashed) {
                $query->onlyTrashed();
            });
    }
}
