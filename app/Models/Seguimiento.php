<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\File;
use App\Models\Entrada;
use Illuminate\Support\Str;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seguimiento extends Model
{

    use HasFactory;
    use ModelosTrait;

    protected $guarded = [];

    protected $casts = [
        'fecha_respuesta' => 'date'
    ];

    protected $appends = ['date_for_editing'];

    public function entrada(){
        return $this->belongsTo(Entrada::class);
    }

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }

    public function getDateForEditingAttribute()
    {
        return $this->fecha_respuesta?->format('d-m-Y');
    }

    public function setDateForEditingAttribute($value)
    {
        $this->fecha_respuesta = Carbon::parse($value);
    }

}
