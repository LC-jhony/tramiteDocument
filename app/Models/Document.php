<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_id',
        'area_id',
        'representation',
        'name',
        'lasta_name',
        'first_name',
        'dni',
        'ruc',
        'empresa',
        'phone',
        'email',
        'addres',
        'code',
        'ate',
        'folio',
        'asunto',
        'file',
    ];
}
