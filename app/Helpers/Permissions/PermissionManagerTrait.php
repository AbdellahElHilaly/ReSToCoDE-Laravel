<?php

namespace App\Helpers\Permissions;


use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\PermissionResource;
use App\Http\Interfaces\Repository\PermissionRepositoryInterface;



trait PermissionManagerTrait {

    // consrante : defaulte permission
    protected $defaultePermissions = [
        'geust' => [
            [
                [
                    'pcontroller_id' => 2,
                    'pmethode_id' => [1, 2],
                ],
                [
                    'pcontroller_id' => 3,
                    'pmethode_id' => [1, 2],
                ],
                [
                    'pcontroller_id' => 4,
                    'pmethode_id' => [1, 2],
                ],
                [
                    'pcontroller_id' => 5,
                    'pmethode_id' => [1, 2],
                ],
                [
                    'pcontroller_id' => 6,
                    'pmethode_id' => [1, 2],
                ],
                [
                    'pcontroller_id' => 8,
                    'pmethode_id' => [1, 2],
                ]
            ]
        ],

        'shef' =>[
            [
                [
                    'pcontroller_id' => 1,
                    'pmethode_id' => [1, 2, 3, 4, 5],
                ],
                [
                    'pcontroller_id' => 2,
                    'pmethode_id' => [1, 2, 3, 4, 5],
                ],
                [
                    'pcontroller_id' => 4,
                    'pmethode_id' => [1, 2, 3, 4, 5],
                ],
                [
                    'pcontroller_id' => 5,
                    'pmethode_id' => [1, 2, 3, 4, 5],
                ],
                [
                    'pcontroller_id' => 6,
                    'pmethode_id' => [1, 2, 3, 4, 5],
                ],
                [
                    'pcontroller_id' => 8,
                    'pmethode_id' => [1, 2, 3, 4, 5],
                ]
            ]
        ],
        'admin' =>[
            [
                [
                    'pcontroller_id' => 1,
                    'pmethode_id' => [1, 2, 5],
                ],
                [
                    'pcontroller_id' => 2,
                    'pmethode_id' => [1, 2, 5],
                ],
                [
                    'pcontroller_id' => 4,
                    'pmethode_id' => [1, 2, 5],
                ],
                [
                    'pcontroller_id' => 5,
                    'pmethode_id' => [1, 2, 5],
                ],
                [
                    'pcontroller_id' => 6,
                    'pmethode_id' => [1, 2, 5],
                ],
                [
                    'pcontroller_id' => 7,
                    'pmethode_id' => [1, 2, 3, 4, 5],
                ],
                [
                    'pcontroller_id' => 8,
                    'pmethode_id' => [1, 2, 5],
                ]
            ]
        ],
    ];

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function addDefaultePermission($user)
        {

            $user_rule = $user->rule->name;
            $user_id = $user->id;

            if (isset($this->defaultePermissions[$user_rule])) {
                $permissions = $this->defaultePermissions[$user_rule][0];
                $attributes = ['user_id' => $user_id];

                foreach ($permissions as $permission) {
                    $attributes['pcontroller_id'] = $permission['pcontroller_id'];
                    $attributes['pmethode_id'] = $permission['pmethode_id'];

                    $this->permissionRepository->store($attributes);

                }
            }
            else throw new \Exception("SYSTEM_CLIENT_ERROR : user rule not found");

        }





}



