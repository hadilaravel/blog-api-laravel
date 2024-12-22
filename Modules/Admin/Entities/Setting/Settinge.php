<?php

namespace Modules\Admin\Entities\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settinge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'keywords',
        'description',
        'icon',
        'logo_footer',
        'logo_header',
    ];
}
