<?php
namespace App\Http\Repositories;
use App\Models\Menu;
use App\Models\User;
use App\Models\FeedBack;
use App\Models\Category;
use App\Helpers\Auth\AuthHelper;
use App\Http\Resources\FeedBackResource;
use App\Http\Interfaces\Repository\FeedBackRepositoryInterface;

class FeedBackRepository implements FeedBackRepositoryInterface{

    use AuthHelper;

    public function all()
    {
        return FeedBackResource::collection(FeedBack::all());
    }

    public function show($id)
    {
        $feedBack = FeedBack::findOrFail($id);
        return new FeedBackResource($feedBack);
    }

    public function store($attributes)
    {
        $attributes['user_id'] = $this->getAuthUser()->id;
        $feedBacks = FeedBack::create($attributes);
        return new FeedBackResource($feedBacks);
    }


    public function clear()
    {
        $this->checkEmpty();
        FeedBack::truncate();
    }


    public function destroy($id)
    {
        $feedBack = FeedBack::findOrFail($id);
        $feedBack->delete();
    }

    public function update($id , $attributes)
    {
        $attributes['user_id'] = $this->getAuthUser()->id;
        $feedBack = FeedBack::findOrFail($id);
        $feedBack->update($attributes);
        return new FeedBackResource($feedBack);
    }

    public function listByMenu($menuId){

        $menus = Menu::with('FeedBacks')->findOrFail($menuId);
        $feedBacks = FeedBackResource::collection($menus->FeedBacks);

        $data['menuName'] = $menus->name;
        $data['FeedBacks'] = $feedBacks;
        return $data;
    }

    public function listByuser($userId){

        $feedBacks = FeedBack::where('user_id' , $userId)->get();
        $data['FeedBacks'] = FeedBackResource::collection($feedBacks);

        $user = User::findOrFail($userId);
        $data['userName'] = $user->name;
        return $data;
    }

    public function listByUserAndMenu($userId, $menuId)
    {
        $menu = Menu::findOrFail($menuId);
        $feedBacks = $menu->FeedBacks()->where('user_id', $userId)->get();

        $data['menuName'] = $menu->name;
        $data['FeedBacks'] = FeedBackResource::collection($feedBacks);

        $user = User::findOrFail($userId);
        $data['userName'] = $user->name;

        return $data;
    }



    public function checkEmpty(){
        if (FeedBack::count() === 0) {
            throw new \Exception('SYSTEM ERROR: FeedBack table is already empty');
        }
    }

}

