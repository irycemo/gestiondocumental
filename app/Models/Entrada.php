<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\Oficina;
use App\Models\Conclusion;
use App\Models\Dependencia;
use App\Models\Seguimiento;
use Illuminate\Support\Str;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entrada extends Model
{

    use HasFactory;
    use ModelosTrait;

    protected $guarded = [];

    protected $casts = [
        'fecha_termino' => 'date',
    ];

    protected $appends = ['date_for_editing'];

    public function origen(){
        return $this->belongsTo(Dependencia::class, 'dependencia_id');
    }

    public function destino(){
        return $this->belongsTo(Oficina::class, 'destinatario');
    }

    public function asignadoA(){
        return $this->belongsToMany(User::class);
    }

    public function seguimientos(){
        return $this->hasMany(Seguimiento::class);
    }

    public function conclusiones(){
        return $this->hasMany(Conclusion::class);
    }

    public function oficina(){
        return $this->belongsTo(Oficina::class);
    }

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }

    protected function limit(): Attribute{
        return Attribute::make(
            get: fn($value) => Str::limit(strip_tags($this->asunto), 100)
        );
    }

    public function getDateForEditingAttribute()
    {
        return $this->fecha_termino?->format('d-m-Y');
    }

    public function setDateForEditingAttribute($value)
    {
        $this->fecha_termino = Carbon::parse($value);
    }

}
