<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\User;

trait ModelosTrait{

    public function creadoPor(){
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function actualizadoPor(){
        return $this->belongsTo(User::class, 'actualizado_por');
    }

    public function getCreatedAtAttribute(): ?string
    {
        return isset($this->attributes['created_at'])
            ? Carbon::parse($this->attributes['created_at'])->format('d-m-Y H:i:s')
            : null;
    }

    public function getUpdatedAtAttribute(): ?string
    {
        return isset($this->attributes['updated_at'])
            ? Carbon::parse($this->attributes['updated_at'])->format('d-m-Y H:i:s')
            : null;
    }

    public function getCreatedAtFormattedAttribute(): ?string
    {
        return $this->getCreatedAtAttribute();
    }

    public function getUpdatedAtFormattedAttribute(): ?string
    {
        return $this->getUpdatedAtAttribute();
    }
}
