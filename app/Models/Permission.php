<?php

namespace App\Models;

use App\Models\Rule;
use App\Models\User;
use App\Models\Pmethode;
use App\Models\Prequest;
use App\Models\Pcontroller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{

    use HasFactory;
    protected $guarded = [];

    public function pcontroller()
    {
        return $this->belongsTo(Pcontroller::class);
    }

    public function pmethode()
    {
        return $this->belongsTo(Pmethode::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
