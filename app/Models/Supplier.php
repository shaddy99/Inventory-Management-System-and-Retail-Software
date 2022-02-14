<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
      'name', 'email', 'phone', 'address', 'city', 'province', 'comments',
    ];
  
    public function person()
    {
      return $this->hasOne(Person::class, 'person_id', 'person_id');
    }
}
