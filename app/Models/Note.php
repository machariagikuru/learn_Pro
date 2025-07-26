<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['sub_strand_id', 'title', 'content', 'grade'];

    public function subStrand()
    {
        return $this->belongsTo(SubStrand::class);
    }

    // Optional: access strand and subject through relationships
    public function strand()
    {
        return $this->subStrand?->strand;
    }

    public function subject()
    {
        return $this->subStrand?->strand?->subject;
    }
}
