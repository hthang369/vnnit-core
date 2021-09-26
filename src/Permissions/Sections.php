<?php

namespace Vnnit\Core\Permissions;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    protected $table = 'sections';

    protected $fillable = [
        'name',
        'code',
        'url',
        'api',
        'description'
    ];
}
