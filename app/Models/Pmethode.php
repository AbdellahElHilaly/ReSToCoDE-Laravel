<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pmethode extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

}
