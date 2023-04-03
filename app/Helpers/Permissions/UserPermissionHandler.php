<?php

namespace App\Helpers\Permissions;


use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\PermissionResource;



trait UserPermissionHandler {


    public function getPermissions($user_id){

        $permissions = Permission::where('user_id', $user_id)->get();




        $data = PermissionResource::collection($permissions);



        // get all controllers name

        $controllers = $this->getControllers($data);

        // check if controller exist in user permissions

        if($controllers){
            $methods = $this->getMethods($data, $controllers[0]);

            // check if methode exist in user permissions

            if($methods){
                $access = [];
                foreach ($controllers as $key => $value) {

                    $access[$value] = $this->getMethods($data, $value);
                }
                return $access;
            }

        }

        return [];










    }


    public function getControllers($data){

        $controllers = [];

        foreach ($data as $key => $value) {

            // add controller name to array without duplicate

            if(!in_array($value->pcontroller->name , $controllers)){

                $controllers[] = $value->pcontroller->name;
            }

        }

        return $controllers;
    }

    public function getMethods($data, $controller){

        $methods = [];

        foreach ($data as $key => $value) {

            // add methode name to array without duplicate

            if($value->pcontroller->name == $controller){

                if(!in_array($value->pmethode->name , $methods)){

                    $methods[] = $value->pmethode->name;
                }
            }

        }

        return $methods;
    }







    public function userPermissions($user_id){

        $data= $this->getPermissions($user_id);

        return $data;
    }





}
