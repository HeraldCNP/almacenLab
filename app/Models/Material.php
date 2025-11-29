<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiales';

    protected $fillable = [
        'codigo',
        'nombre_material',
        'categoria_id',
        'unidad_medida_id',
        'stock_minimo',
        'stock_actual',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class);
    }

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }
}
