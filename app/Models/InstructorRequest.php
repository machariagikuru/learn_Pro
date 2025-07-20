<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstructorRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'message',
    ];

    // يمكنك إضافة علاقة مع نموذج المستخدم إن كنت تحتاج ذلك
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
