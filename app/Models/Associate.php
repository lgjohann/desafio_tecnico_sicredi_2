<?php

namespace App\Models;

use App\Enums\BrazilianState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Associate extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'telephone',
        'city',
        'state',
    ];
    protected $casts = [
        'state' => BrazilianState::class,
    ];
}
