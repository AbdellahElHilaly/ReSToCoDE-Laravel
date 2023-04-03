<?php

namespace App\Helpers\Permissions;


use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\PermissionResource;



trait PermissionFormeHanler {

    public function handelPermissionsData($permissionsCollection){
        $result = [];

        foreach ($permissionsCollection as $permission) {
            $user = $permission->user->name;
            $rule = $permission->user->rule->name;
            $controller = $permission->pcontroller->name;
            $method = $permission->pmethode->name;

            if (!isset($result[$user])) {
                $result[$user] = [
                    'rule' => $rule,
                    'permission' => []
                ];
            }

            if (!isset($result[$user]['permission'][$controller])) {
                $result[$user]['permission'][$controller] = [];
            }

            $result[$user]['permission'][$controller][] = $method;
        }

        return $result;

    }

}



