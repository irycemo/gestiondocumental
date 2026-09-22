<?php

namespace App\Models;

use App\Models\Phase;
use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{

    protected $guarded = ['id'];

    use CreatedByTrait;

    public function phases():HasMany
    {
        return $this->hasMany(Phase::class);
    }

    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

}
