<?php

namespace App\Http\Interfaces\Repository;

use App\Models\User;

interface PermissionRepositoryInterface
{

    public function getPermissions();
    public function store($attributes);


}
