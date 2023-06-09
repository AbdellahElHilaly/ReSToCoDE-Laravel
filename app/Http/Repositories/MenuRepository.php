<?php
namespace App\Http\Repositories;
use App\Models\Menu;
use App\Models\User;
use App\Models\Category;
use App\Helpers\Auth\AuthHelper;
use App\Helpers\Media\MediaHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\MenuResource;
use App\Http\Interfaces\Repository\MenuRepositoryInterface;

class MenuRepository implements MenuRepositoryInterface{

    use AuthHelper;

    public function all()
    {
        return MenuResource::collection(Menu::with(['meals' => function($query){
            $query->withPivot('quantity');
        }])->get());
    }



    public function show($id)
    {

        $menuCheck = Menu::findOrFail($id);
        return MenuResource::collection(Menu::with(['meals' => function($query){
            $query->withPivot('quantity');
        }])->where('id', $id)->get());

    }

    public function store($attributes)
    {
        $auth_id = $this->getAuthUser()->id;
        $attributes['user_id'] = $auth_id;
        $menu = Menu::create($attributes);
        return new MenuResource($menu);
    }

    public function clear()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $this->checkEmpty();
        DB::table('meal_menu')->truncate();
        Menu::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    }


    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->meals()->detach();
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

        if($userId === "auth") $userId = $this->getAuthUser()->id;
        if($this->getAuthUser()->rule_id !==2) throw new \Exception('SYSTEM_CLIENT_ERROR : You are not a shef to see your meals');


        $user = User::with('Menus')->findOrFail($userId);
        $menus = MenuResource::collection($user->Menus);

        $data['userName'] = $user->name;
        $data['Menus'] = MenuResource::collection($menus);
        return $data;
    }



    public function checkEmpty(){
        if (Menu::count() === 0) {
            throw new \Exception('SYSTEM_CLIENT_ERROR : Menu table is already empty');
        }
    }

    public function showToday(){
        $menus = Menu::whereDate('date', date('Y-m-d'))->get();
        return MenuResource::collection($menus);
    }

}

