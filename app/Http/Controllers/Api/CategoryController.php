<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Response;
use App\Helpers\ApiResponceHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Exceptions\ErorrExeptionsHandler;
use App\Http\Interfaces\Repository\CateroyRepositoryInterface;

class CategoryController extends Controller
{

    use ErorrExeptionsHandler;
    use ApiResponceHandler;

    protected CateroyRepositoryInterface $categoryRepository;

    public function __construct(CateroyRepositoryInterface $categoryRepository)
    {
        $this->middleware('auth');
        $this->middleware('account.verified');
        $this->middleware('permission');

        $this->categoryRepository = $categoryRepository;
    }


    public function index()
    {
        try {
            $categories  = $this->categoryRepository->all();
            if($categories->count()==0)return $this->apiResponse($categories, true ,"No categories found !" ,Response::HTTP_NOT_FOUND);
            return $this->apiResponse($categories, true, "Successfully retrieved " . $categories->count() . " Categories" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function store(CategoryStoreRequest $request)
    {
        try {
            $attributes = $request->validated();
            $category = $this->categoryRepository->store($attributes);
            return $this->apiResponse($category , true, 'Category created successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function show(string $id)
    {
        try {
            $categories  = $this->categoryRepository->show($id);
            return $this->apiResponse($categories, true, "Successfully retrieved the Category" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function update(CategoryUpdateRequest $request, string $id)
    {
        try {
            $attributes = $request->all();
            $category = $this->categoryRepository->update($id, $attributes);
            return $this->apiResponse($category, true, 'Category updated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function destroy(string $id)
    {
        try {
            if ($id == "clear") {
                $this->categoryRepository->clear();
                return $this->apiResponse(null, true, 'All categories have been deleted successfully', Response::HTTP_OK);
            } else {
                $this->categoryRepository->destroy($id);
                return $this->apiResponse(null, true, 'Category deleted successfully', Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

}
