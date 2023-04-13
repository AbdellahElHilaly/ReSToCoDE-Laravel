<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Helpers\ApiResponceHandler;
use App\Http\Controllers\Controller;
use App\Exceptions\ErorrExeptionsHandler;
use App\Http\Requests\FeedBack\FeedBackStoreRequest;
use App\Http\Requests\FeedBack\FeedBackUpdateRequest;
use App\Http\Interfaces\Repository\FeedBackRepositoryInterface;




class FeedBackController extends Controller
{
    protected FeedBackRepositoryInterface $feedBackRepository;

    use ErorrExeptionsHandler;
    use ApiResponceHandler;

    public function __construct(FeedBackRepositoryInterface $feedBackRepository)
    {
        try{
            // $this->middleware('auth');
            // $this->middleware('account.verified');
            // $this->middleware('permission');
            $this->feedBackRepository = $feedBackRepository;

        }catch (\Exception $e) {
            return $this->handleException($e);
        }

    }


    public function index()
    {

        try {
            $feedBacks  = $this->feedBackRepository->all();
            if($feedBacks->count()==0)return $this->apiResponse($feedBacks, true ,"No FeedBacks found !" ,Response::HTTP_NOT_FOUND);
            return $this->apiResponse($feedBacks, true, "Successfully retrieved " . $feedBacks->count() . " FeedBacks" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }

    }



    public function store(FeedBackStoreRequest $request)
    {
        try {
            $attributes = $request->validated();
            $feedBack = $this->feedBackRepository->store($attributes);
            return $this->apiResponse($feedBack , true, 'FeedBack created successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



    public function show(string $id)
    {
        try {
            if (strpos($id, 'user=') === 0 && strpos($id, '-menu=') !== false) {
                $ids = explode('-menu=', str_replace('user=', '', $id));
                $userId = $ids[0];
                $menuId = $ids[1];


                $feedBack  = $this->feedBackRepository->listByUserAndMenu($userId , $menuId)['FeedBacks'];
                $userName = $this->feedBackRepository->listByUserAndMenu($userId , $menuId)['userName'];
                $menuName = $this->feedBackRepository->listByUserAndMenu($userId , $menuId)['menuName'];
                if($feedBack->count()==0) {
                    return $this->apiResponse($feedBack, false, "No FeedBack were found for the [ ".$userName." ] user and the [ ".$menuName." ] menu.", Response::HTTP_NOT_FOUND);
                } else {
                    $feedBackCount = $feedBack->count();
                    return $this->apiResponse($feedBack, true, "Successfully retrieved ".$feedBackCount." FeedBack(s) for the [ ".$userName." ] user and the [ ".$menuName." ] menu.", Response::HTTP_OK);
                }
            }

            elseif (strpos($id, 'menu=') === 0) {
                $menuId = substr($id, strlen('menu='));
                $feedBack  = $this->feedBackRepository->listByMenu($menuId)['FeedBacks'];
                $menuName = $this->feedBackRepository->listByMenu($menuId)['menuName'];
                if($feedBack->count()==0) {
                    return $this->apiResponse($feedBack, false, "No FeedBack were found for the [ ".$menuName." ] menu.", Response::HTTP_NOT_FOUND);
                } else {
                    $feedBackCount = $feedBack->count();
                    return $this->apiResponse($feedBack, true, "Successfully retrieved ".$feedBackCount." FeedBack(s) for the [ ".$menuName." ] menu.", Response::HTTP_OK);
                }
            }

            elseif(strpos($id, 'user=') === 0){
                $userId = substr($id, strlen('user='));
                $feedBack  = $this->feedBackRepository->listByuser($userId)['FeedBacks'];
                $userName = $this->feedBackRepository->listByuser($userId)['userName'];
                if($feedBack->count()==0) {
                    return $this->apiResponse($feedBack, false, "No FeedBack were found for the [ ".$userName." ] user.", Response::HTTP_NOT_FOUND);
                } else {
                    $feedBackCount = $feedBack->count();
                    return $this->apiResponse($feedBack, true, "Successfully retrieved ".$feedBackCount." FeedBack(s) for the [ ".$userName." ] user.", Response::HTTP_OK);
                }
            }
            $feedBack  = $this->feedBackRepository->show($id);
            return $this->apiResponse($feedBack, true, "Successfully retrieved the FeedBack" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function update(FeedBackUpdateRequest $request, string $id)
    {
        try {
            $attributes = $request->all();
            $feedBack = $this->feedBackRepository->update($id, $attributes);
            return $this->apiResponse($feedBack, true, 'FeedBack updated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(string $id)
    {
        try {
            if ($id == "all") {
                $this->feedBackRepository->clear();
                return $this->apiResponse(null, true, 'All FeedBacks have been deleted successfully', Response::HTTP_OK);
            } else {
                $this->feedBackRepository->destroy($id);
                return $this->apiResponse(null, true, 'FeedBack deleted successfully', Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
