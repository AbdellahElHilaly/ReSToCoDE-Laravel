<?php
namespace App\Http\Repositories;
use App\Http\Interfaces\Repository\CateroyRepositoryInterface;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryRepository implements CateroyRepositoryInterface{

    public function all()
    {
        return CategoryResource::collection(Category::all());
    }
    public function show($id)
    {
        $category =  Category::findOrFail($id);
        return new CategoryResource($category);
    }

    public function store($attributes)
    {
        $category = Category::create($attributes);
        return new CategoryResource($category);
    }

    public function clear()
    {
        $this->checkEmpty();
        Category::truncate();
    }


    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }

    public function update($id , $attributes)
    {
        $category = Category::findOrFail($id);
        $category->update($attributes);
        return new CategoryResource($category);
    }


    public function checkEmpty(){
        if (Category::count() === 0) {
            throw new \Exception('Category table is already empty');
        }
    }


}

