<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['fileable_id', 'fileable_type', 'url'];

    public function fileable(){
        return $this->morphTo();
    }

    public function getLink(){

        if(app()->isProduction()){

            if(Str::contains($this->url, config('services.ses.ruta_archivos'))){
                info(config('services.ses.ruta_archivos'));

                return Storage::disk('s3')->temporaryUrl($this->url, now()->addMinutes(10));

            }else{

                return Storage::disk('s3')->temporaryUrl(config('services.ses.ruta_documento_entrada') . '/' . $this->url, now()->addMinutes(10));

            }

        }else{

            return $this->archivos()->where('descripcion', 'documento_entrada')->latest()->first()
                    ? Storage::disk('pdfs')->url($this->url)
                    : null;

        }

    }

}
