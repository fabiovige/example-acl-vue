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
        'parent_id',
        'employee_id'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    // Relacionamento com o pai/responsável
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Relacionamento com o funcionário
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function getFormattedBirthDateAttribute()
    {
        return $this->birth_date->format('d/m/Y');
    }

    public function getParentInfoAttribute()
    {
        return $this->parent ? $this->parent->name : 'Não definido';
    }

    protected $appends = ['formatted_birth_date', 'parent_info'];
}
