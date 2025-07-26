<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubStrand extends Model
{
    use HasFactory;

    protected $fillable = ['strand_id', 'name', 'slug'];

    public function strand()
    {
        return $this->belongsTo(Strand::class);
    }
}
