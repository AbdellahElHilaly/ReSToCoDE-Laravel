<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Helpers\ApiResponceHandler;
use App\Http\Controllers\Controller;
use App\Exceptions\ErorrExeptionsHandler;
use App\Http\Requests\Menu\MenuStoreRequest;
use App\Http\Requests\Menu\MenuUpdateRequest;
use App\Http\Interfaces\Repository\MenuRepositoryInterface;




class MenuController extends Controller
{
    protected MenuRepositoryInterface $menuRepository;

    use ErorrExeptionsHandler;
    use ApiResponceHandler;

    public function __construct(MenuRepositoryInterface $menuRepository)
    {
        try{
            $this->middleware('auth'); // url of middleware :: app\Http\Middleware\Authenticate.php
            $this->middleware('account.verified');
            $this->middleware('permission');
            $this->middleware('device.trust');
            
            $this->menuRepository = $menuRepository;

        }catch (\Exception $e) {
            return $this->handleException($e);
        }

    }


    public function index()
    {
        try {
            $menus  = $this->menuRepository->all();
            if($menus->count()==0)return $this->apiResponse($menus, true ,"No Menus found !" ,Response::HTTP_NOT_FOUND);
            return $this->apiResponse($menus, true, "Successfully retrieved " . $menus->count() . " Menus" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }

    }



    public function store(MenuStoreRequest $request)
    {
        try {
            $attributes = $request->validated();
            $menu = $this->menuRepository->store($attributes);
            return $this->apiResponse($menu , true, 'Menu created successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



    public function show(string $id)
    {
        try {
            if (strpos($id, 'today') === 0) {
                $menu = $this->menuRepository->showToday();
                if($menu->count()==0) {
                    return $this->apiResponse($menu, false, "No Menu were found for today.", Response::HTTP_NOT_FOUND);
                } else {
                    return $this->apiResponse($menu, true, "Successfully retrieved " . $menu->count() . " Menu for today.", Response::HTTP_OK);
                }
            }
            if (strpos($id, 'category=') === 0) {
                $categoryId = substr($id, strlen('category='));
                $menu  = $this->menuRepository->listByCategory($categoryId)['Menus'];
                $categoryName = $this->menuRepository->listByCategory($categoryId)['categoryName'];
                if($menu->count()==0) {
                    return $this->apiResponse($menu, false, "No Menu were found for the [ ".$categoryName." ] category.", Response::HTTP_NOT_FOUND);
                } else {
                    $menuCount = $menu->count();
                    return $this->apiResponse($menu, true, "Successfully retrieved ".$menuCount." Menu(s) for the [ ".$categoryName." ] category.", Response::HTTP_OK);
                }
            }elseif(strpos($id, 'shef=') === 0){
                $shefId = substr($id, strlen('shef='));
                $menu  = $this->menuRepository->listByUser($shefId)['Menus'];
                $shefName = $this->menuRepository->listByUser($shefId)['userName'];
                if($menu->count()==0) {
                    return $this->apiResponse($menu, false, "No Menu were found for the [ ".$shefName." ] shef.", Response::HTTP_NOT_FOUND);
                } else {
                    $menuCount = $menu->count();
                    return $this->apiResponse($menu, true, "Successfully retrieved ".$menuCount." Menu(s) for the [ ".$shefName." ] shef.", Response::HTTP_OK);
                }
            }
            $menu  = $this->menuRepository->show($id);
            return $this->apiResponse($menu, true, "Successfully retrieved the Menu" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function update(MenuUpdateRequest $request, string $id)
    {
        try {
            $attributes = $request->all();
            $menu = $this->menuRepository->update($id, $attributes);
            return $this->apiResponse($menu, true, 'Menu updated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(string $id)
    {
        try {
            if ($id == "all") {
                $this->menuRepository->clear();
                return $this->apiResponse(null, true, 'All Menus have been deleted successfully', Response::HTTP_OK);
            } else {
                $this->menuRepository->destroy($id);
                return $this->apiResponse(null, true, 'Menu deleted successfully', Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



}
