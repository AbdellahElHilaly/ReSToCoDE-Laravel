<?php

namespace App\Http\Controllers\API;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ApiResponceHandler;
use App\Http\Controllers\Controller;
use App\Exceptions\ErorrExeptionsHandler;
use App\Helpers\Permissions\PermissionFormeHanler;
use App\Http\Requests\Permission\PermissionStoreRequest;
use App\Http\Interfaces\Repository\PermissionRepositoryInterface;

class PermissionController extends Controller
{

    use ErorrExeptionsHandler;
    use ApiResponceHandler;
    use PermissionFormeHanler;

    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        try{
            $this->middleware('auth');
            $this->middleware('account.verified');
            $this->middleware('permission');
            $this->middleware('device.trust');
            
            $this->permissionRepository = $permissionRepository;

        }catch (\Exception $e) {
            return $this->handleException($e);
        }

    }

    public function index()
    {
        try {
            $permissions = $this->permissionRepository->getPermissions();
            $permissions = $this->handelPermissionsData($permissions);
            return $this->apiResponse($permissions, true, "Successfully retrieved "  , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(PermissionStoreRequest $request)
    {
        try {
            $attributes = $request->validated();
            $permissions = $this->permissionRepository->store($attributes);
            $permissions = $this->handelPermissionsData($permissions);
            return $this->apiResponse($permissions, true, "Permission created successfully" , Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function show(Permission $permission)
    {
        //
    }


    public function update(Request $request, Permission $permission)
    {
        //
    }


    public function destroy(string $id)
    {
        try {
            $permission = $this->permissionRepository->destroy($id);
            return $this->apiResponse($permission, true, "Permission deleted successfully" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

}
