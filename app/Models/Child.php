<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Child extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'birth_date',
        'ethnicity',
        'gender',
        'address',
        'address_number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'zipcode',
        'father_name',
        'father_phone',
        'mother_name',
        'mother_phone',
        'user_id'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    // Relacionamento com o usuário (pai/responsável)
    public function parent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFormattedBirthDateAttribute()
    {
        return $this->birth_date->format('d/m/Y');
    }

    protected $appends = ['formatted_birth_date'];
}
