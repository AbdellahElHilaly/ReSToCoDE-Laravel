<?php
namespace App\Http\Repositories;
use App\Models\Menu;
use App\Models\User;
use App\Models\Category;
use App\Helpers\Auth\AuthHelper;
use App\Helpers\Media\MediaHelper;
use App\Http\Resources\MenuResource;
use App\Http\Interfaces\Repository\MenuRepositoryInterface;

class MenuRepository implements MenuRepositoryInterface{

    use AuthHelper;

    public function all()
    {
        return MenuResource::collection(Menu::with('comments')->get());
    }

    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return new MenuResource($menu);
    }

    public function store($attributes)
    {
        $auth_id = $this->getAuthUser()->id; // frome hepler
        $attributes['user_id'] = $auth_id;
        // get meal_id array from request
        $meal_ids = $attributes['meal_ids'];
        // remove meal_ids from attributes
        unset($attributes['meal_ids']);
        // create menu
        $menu = Menu::create($attributes);
        // i have menu_meal table in database  , attach meal_ids to menu
        $menu->meals()->attach($meal_ids);
        return new MenuResource($menu);
    }



    public function clear()
    {
        $this->checkEmpty();
        Menu::truncate();
    }


    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
    }

    public function update($id , $attributes)
    {
        $attributes['user_id'] = $this->getAuthUser()->id;
        $menu = Menu::findOrFail($id);
        $menu->update($attributes);
        return new MenuResource($menu);
    }


    public function listByUser($userId){

        $user = User::with('Menus')->findOrFail($userId);
        $menus = MenuResource::collection($user->Menus);

        $data['userName'] = $user->name;
        $data['Menus'] = MenuResource::collection($menus);
        return $data;
    }



    public function checkEmpty(){
        if (Menu::count() === 0) {
            throw new \Exception('SYSTEM ERROR: Menu table is already empty');
        }
    }

}

