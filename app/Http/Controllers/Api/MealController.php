<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Helpers\ApiResponceHandler;
use App\Http\Controllers\Controller;
use App\Exceptions\ErorrExeptionsHandler;
use App\Http\Requests\Meal\MealStoreRequest;
use App\Http\Requests\Meal\MealUpdateRequest;
use App\Http\Interfaces\Repository\MealRepositoryInterface;




class MealController extends Controller
{
    protected MealRepositoryInterface $mealRepository;

    use ErorrExeptionsHandler;
    use ApiResponceHandler;

    public function __construct(MealRepositoryInterface $mealRepository)
    {
        try{
            // $this->middleware('auth');
            // $this->middleware('account.verified');
            // $this->middleware('permission');
            $this->mealRepository = $mealRepository;

        }catch (\Exception $e) {
            return $this->handleException($e);
        }

    }


    public function index()
    {

        try {
            $meals  = $this->mealRepository->all();
            if($meals->count()==0)return $this->apiResponse($meals, true ,"No meals found !" ,Response::HTTP_NOT_FOUND);
            return $this->apiResponse($meals, true, "Successfully retrieved " . $meals->count() . " meals" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



    public function store(MealStoreRequest $request)
    {
        try {
            $attributes = $request->validated();
            $meal = $this->mealRepository->store($attributes);
            return $this->apiResponse($meal , true, 'meal created successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



    public function show(string $id)
    {
        try {
            if (strpos($id, 'category=') === 0) {
                $categoryId = substr($id, strlen('category='));
                $meal  = $this->mealRepository->listByCategory($categoryId)['Meals'];
                $categoryName = $this->mealRepository->listByCategory($categoryId)['categoryName'];
                if($meal->count()==0) {
                    return $this->apiResponse($meal, false, "No meal were found for the [ ".$categoryName." ] category.", Response::HTTP_NOT_FOUND);
                } else {
                    $mealCount = $meal->count();
                    return $this->apiResponse($meal, true, "Successfully retrieved ".$mealCount." meal(s) for the [ ".$categoryName." ] category.", Response::HTTP_OK);
                }
            }elseif(strpos($id, 'developer=') === 0){
                $developerId = substr($id, strlen('developer='));
                $meal  = $this->mealRepository->listByUser($developerId)['Meals'];
                $developerName = $this->mealRepository->listByUser($developerId)['userName'];
                if($meal->count()==0) {
                    return $this->apiResponse($meal, false, "No meal were found for the [ ".$developerName." ] developer.", Response::HTTP_NOT_FOUND);
                } else {
                    $mealCount = $meal->count();
                    return $this->apiResponse($meal, true, "Successfully retrieved ".$mealCount." meal(s) for the [ ".$developerName." ] developer.", Response::HTTP_OK);
                }
            }
            $meal  = $this->mealRepository->show($id);
            return $this->apiResponse($meal, true, "Successfully retrieved the meal" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function update(MealUpdateRequest $request, string $id)
    {
        try {
            $attributes = $request->all();
            $meal = $this->mealRepository->update($id, $attributes);
            return $this->apiResponse($meal, true, 'meal updated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(string $id)
    {
        try {
            if ($id == "all") {
                $this->mealRepository->clear();
                return $this->apiResponse(null, true, 'All Meals have been deleted successfully', Response::HTTP_OK);
            } else {
                $this->mealRepository->destroy($id);
                return $this->apiResponse(null, true, 'Meal deleted successfully', Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



}
