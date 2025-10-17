<?php

namespace App\Models;

use App\Models\User;
use App\Traits\ModelosTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oficina extends Model
{
    use HasFactory;
    use ModelosTrait;

    protected $guarded = [];

    public function uTitular(){
        return $this->belongsTo(User::class, 'titular');
    }

    public function usuarios(){
        return $this->HasMany(User::class);
    }

}
