<?php

namespace App\Models;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'date',
        'folio',
        'asunto',
        'file',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(
            related: Area::class,
            foreignKey: 'area_id',
        );
    }
    public function type(): BelongsTo
    {
        return $this->belongsTo(
            related: Type::class,
            foreignKey: 'type_id'
        );
    }
}
