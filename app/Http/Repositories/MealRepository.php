<?php
namespace App\Http\Repositories;
use App\Models\Meal;
use App\Models\User;
use App\Models\Category;
use App\Helpers\Auth\AuthHelper;
use App\Helpers\Media\MediaHelper;
use App\Http\Resources\MealResource;
use App\Http\Interfaces\Repository\MealRepositoryInterface;

class MealRepository implements MealRepositoryInterface{

    use AuthHelper;
    use MediaHelper;

    public function all()
    {
        return MealResource::collection(Meal::all());
    }

    public function show($id)
    {
        $meal = Meal::findOrFail($id);
        return new MealResource($meal);
    }

    public function store($attributes)
    {
        $auth_id = $this->getAuthUser()->id; // frome hepler
        $attributes['user_id'] = $auth_id;
        // atribute have an image file
        $attributes['image'] = $this->uploadImage($attributes['image']);
        // uploadImage function from helper
        $meals = Meal::create($attributes);
        return new MealResource($meals);
    }


    public function clear()
    {
        $this->checkEmpty();
        Meal::truncate();
    }


    public function destroy($id)
    {
        $meal = Meal::findOrFail($id);
        $meal->delete();
    }

    public function update($id , $attributes)
    {
        $attributes['user_id'] = $this->getAuthUser()->id;
        $meal = Meal::findOrFail($id);
        $meal->update($attributes);
        return new MealResource($meal);
    }

    public function listByCategory($categoryId){

        $category = Category::with('Meals')->findOrFail($categoryId);
        $meals = MealResource::collection($category->Meals);
        $data['categoryName'] = $category->name;
        $data['Meals'] = $meals;
        return $data;
    }

    public function listByUser($userId){

        $user = User::with('Meals')->findOrFail($userId);
        $meals = MealResource::collection($user->Meals);

        $data['userName'] = $user->name;
        $data['Meals'] = MealResource::collection($meals);
        return $data;
    }


    public function checkEmpty(){
        if (Meal::count() === 0) {
            throw new \Exception('SYSTEM ERROR: Meal table is already empty');
        }
    }

}
