<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimiento extends Model
{
    protected $fillable = [
        'lote_id',
        'user_id',
        'tipo',
        'cantidad',
        'motivo',
        'fecha_movimiento',
    ];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
