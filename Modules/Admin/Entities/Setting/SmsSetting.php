<?php

namespace Modules\Admin\Entities\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    use HasFactory;

    protected $fillable = ['username' , 'password' , 'from'];

}
