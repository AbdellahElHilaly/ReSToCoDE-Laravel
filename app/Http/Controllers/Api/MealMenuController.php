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
        return response("Hello");
    }

    public function store(MealMenuStoreRequest $request)
    {
        try {

            $data = $request->validated();
            $meal = Meal::findOrfail($data['meal_id']);
            $menu = Menu::findOrfail($data['menu_id']);

            $this->checkRowExists($meal, $menu);

            $this->checkUniqCategory($meal, $menu);
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

            if($this->checkRowExists($meal, $menu)){
                throw new \Exception("Meal not found in menu");
            }

            $meal->menus()->detach($menu);
            return $this->apiResponse(null , true, 'Meal removed from menu successfully', Response::HTTP_OK);
        }catch (\Exception $e) {
            return $this->handleException($e);
        }

    }



    private function checkUniqCategory($meal, $menu) {
        // get all  meals from the menu
        $meals = $menu->meals()->get();
        // check if the meal category is already in the menu
        foreach($meals as $m) {
            if($m->category_id == $meal->category_id) {
                throw new \Exception("Meal category already exists in menu");
            }
        }
    }

    private function checkRowExists($meal, $menu) {

        if($meal->menus()->where('menu_id', $menu->id)->exists())
        throw new \Exception('SYSTEM_CLIENT_ERROR : Meal already exists in menu' , 400);
    }

    private function checkMainMealAfectImage($meal, $menu){
        if($meal->category_id == 1) {
            $menu->image = $meal->image;
            $menu->save();
        }
    }


}



