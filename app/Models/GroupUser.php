<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupUser extends Model
{
    protected $guarded = ['id'];

    protected $table = 'group_user';

    public function groups_added():BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

}
