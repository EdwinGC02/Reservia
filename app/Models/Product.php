<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    /**
     * Tabla asociada (opcional si coincide con el plural del modelo)
     */
    protected $table = 'products';

    /**
     * Campos que se pueden asignar en masa (mass assignment)
     */
    protected $fillable = [
        'code',
        'name',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    /**
     * Laravel ya maneja created_at y updated_at
     */
    public $timestamps = true;

    /**
     * Casts automáticos
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Mutadores para mantener trazabilidad
     */
    protected static function boot()
    {
        parent::boot();

        // Al crear un registro
        static::creating(function ($model) {
            $userId = Auth::id() ?? null;
            $model->created_by = $userId;
            $model->updated_by = $userId;
        });

        // Al actualizar un registro
        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? $model->updated_by;
        });
    }

    /**
     * Relación opcional con usuarios (si tienes la tabla users)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
