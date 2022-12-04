<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    use HasFactory;
    protected $table = 'enterprise';
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'fantasy_name',
        'cnpj',
        'uuid',
        'email',
        'timezone',
        'phone1',
        'theft_emergency_tel',
        'assistance_emergency_tel'
    ];
}
