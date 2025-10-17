<?php

namespace App\Models;

use App\Models\File;
use App\Models\Entrada;
use App\Models\Oficina;
use Illuminate\Support\Str;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conclusion extends Model
{

    use HasFactory;
    use ModelosTrait;

    protected $guarded = [];

    public function entrada(){
        return $this->belongsTo(Entrada::class);
    }

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }

    public function oficina(){
        return $this->belongsTo(Oficina::class);
    }

    protected function limit(): Attribute{
        return Attribute::make(
            get: fn($value) => Str::limit(strip_tags($this->comentario), 100)
        );
    }

}
