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
        return MenuResource::collection(Menu::all());
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
        $menus = Menu::create($attributes);
        return new MenuResource($menus);
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

