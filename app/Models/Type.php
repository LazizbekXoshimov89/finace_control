<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'user_id',
        'is_input'
    ];

  public function User()
  {
    return $this->hasOne(User::class,'id','user_id');
  }
}

