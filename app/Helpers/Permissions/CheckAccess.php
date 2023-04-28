<?php

namespace App\Helpers\Permissions;

trait CheckAccess {


    // check if p_request is in p_access
    public function AccessVerified($result){

        $controller = $result['p_request']['controller'];
        $method = $result['p_request']['method'];
        $find_by = $result['p_request']['find_by'];

        $access = $result['p_access'];

        if (array_key_exists($controller, $access)) {
            if (in_array($method, $access[$controller])) {
                return true;
            }
        }

        return false;

    }

    // if "find_by": "auth" the user shoulde be a shef

    public function findByVirefied($result){

        $find_by_requested = $result['p_request']['find_by'];
        $rule = $result['rule'];

        if ($find_by_requested == 'auth') {
            if ($rule == 'shef') {
                return true;
            }

            return false;
        }

        return true;

    }


}
