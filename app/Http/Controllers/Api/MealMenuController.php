<?php

namespace App\Http\Controllers\Api;

use App\Models\Meal;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ApiResponceHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Exceptions\ErorrExeptionsHandler;
use App\Http\Requests\MealMenu\MealMenuStoreRequest;
use App\Http\Requests\MealMenu\MealMenuUpdateRequest;

class MealMenuController extends Controller
{

    use ErorrExeptionsHandler;
    use ApiResponceHandler;


    public function __construct()
    {
        try{
            // $this->middleware('auth');
            // $this->middleware('account.verified');
            // $this->middleware('permission');

        }catch (\Exception $e) {
            return $this->handleException($e);
        }

    }


    public function index()
    {
        try {
            $menus = Menu::all();
            return $this->apiResponse(MenuResource::collection($menus)  , true, 'Menus retrieved successfully', Response::HTTP_OK);
        }catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(MealMenuStoreRequest $request)
    {
        try {

            $data = $request->validated();
            $meal = Meal::findOrfail($data['meal_id']);
            $menu = Menu::findOrfail($data['menu_id']);

            if($this->rowAlredyExists($meal, $menu)){
                $this->updateQuantity($meal, $menu, $data['quantity']);
                return $this->apiResponse(new MenuResource($menu)  , true, 'Meal quantity updated successfully', Response::HTTP_OK);
            }

            if($this->categoryAlredyExist($meal, $menu))
                return $this->apiResponse(null  , false, 'Meal category already exist in menu', Response::HTTP_BAD_REQUEST);

            $this->checkMainMealAfectImage($meal, $menu);

            $meal->menus()->attach($menu, ['quantity' => $data['quantity']]);


            return $this->apiResponse(new MenuResource($menu)  , true, 'Meal added to menu successfully', Response::HTTP_CREATED);

        }catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function destroy(string $id)
    {

        try {
            $ids = explode('&', $id);
            $meal_id = explode('=', $ids[0])[1];
            $menu_id = explode('=', $ids[1])[1];

            $meal = Meal::findOrfail($meal_id);
            $menu = Menu::findOrfail($menu_id);

            if(!$this->rowAlredyExists($meal, $menu)){
                return $this->apiResponse(null  , true, 'Meal not found in menu', Response::HTTP_BAD_REQUEST);
            }

            $meal->menus()->detach($menu);
            return $this->apiResponse(null , true, 'Meal removed from menu successfully', Response::HTTP_OK);
        }catch (\Exception $e) {
            return $this->handleException($e);
        }

    }



    private function categoryAlredyExist($meal, $menu) {
        // get all  meals from the menu
        $meals = $menu->meals()->get();
        // check if the meal category is already in the menu
        foreach($meals as $m) {
            if($m->category_id == $meal->category_id)
                return true;
        }

        return false;
    }

    private function rowAlredyExists($meal, $menu) {
        return $meal->menus()->where('menu_id', $menu->id)->exists();
    }

    private function checkMainMealAfectImage($meal, $menu){
        if($meal->category_id == 1) {
            $menu->image = $meal->image;
            $menu->save();
        }
    }


    private function updateQuantity($meal, $menu, $quantity) {
        $meal->menus()->updateExistingPivot($menu, ['quantity' => $quantity]);
    }


}



