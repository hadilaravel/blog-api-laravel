<?php

namespace Modules\Access\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'status' , 'description'];

    public function users ()
    {
        return $this->belongsToMany(User::class );
    }

    public function permissions ()
    {
        return $this->belongsToMany(Permission::class );
    }
}
