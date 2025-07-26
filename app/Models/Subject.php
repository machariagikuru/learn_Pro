<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function strands()
    {
        return $this->hasMany(Strand::class);
    }
}
