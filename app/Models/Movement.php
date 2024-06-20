<?php

namespace App\Models;

use App\Models\Area;
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
            Document::class

        );
    }
    public function areaOrigen(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_origen_id');
    }

    public function destinationArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'destination_area_id');
    }
}
