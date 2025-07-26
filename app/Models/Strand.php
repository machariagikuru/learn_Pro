<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Strand extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'name', 'slug'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function subStrands()
    {
        return $this->hasMany(SubStrand::class);
    }
}
