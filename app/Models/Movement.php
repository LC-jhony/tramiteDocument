<?php

namespace App\Models;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movement extends Model
{
    use HasFactory;
    protected $fillable = [
        'document_id',
        'area_origen_id',
        'destination_area_id',
        'date_movement',
        'description',
        'status',
        'user_id',
        'mov_file',
        'mov_description_origen',
    ];
    public function document(): BelongsTo
    {
        return $this->belongsTo(
            related: Document::class,
            foreignKey: 'document_id'
        );
    }
}
