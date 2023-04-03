<?php
namespace App\Http\Repositories;

use App\Models\Permission;
use App\Http\Resources\PermissionResource;
use App\Http\Interfaces\Repository\PermissionRepositoryInterface;

class PermissionRepository implements PermissionRepositoryInterface{

        public function getPermissions()
        {

            $permissions = Permission::with('user', 'pcontroller', 'pmethode')->get();
            return PermissionResource::collection($permissions);

        }


        public function store($attributes)
{
    $user_id = $attributes['user_id'];
    $pcontroller_id = $attributes['pcontroller_id'];
    $pmethode_ids = $attributes['pmethode_id'];

    $permissions = [];
    foreach ($pmethode_ids as $pmethode_id) {
        $permissions[] = [
            'user_id' => $user_id,
            'pcontroller_id' => $pcontroller_id,
            'pmethode_id' => $pmethode_id
        ];
    }


    $permissions = Permission::insert($permissions);

    //get permissions have user_id = $user_id

    $permissions = Permission::with('user', 'pcontroller', 'pmethode')->where('user_id', $user_id)->get();

    return PermissionResource::collection($permissions);

}

}

