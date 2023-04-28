<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Helpers\ApiResponceHandler;
use App\Http\Controllers\Controller;
use App\Exceptions\ErorrExeptionsHandler;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Http\Interfaces\Repository\CommentRepositoryInterface;




class CommentController extends Controller
{
    protected CommentRepositoryInterface $commentRepository;

    use ErorrExeptionsHandler;
    use ApiResponceHandler;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        try{
            $this->middleware('auth');
            $this->middleware('account.verified');
            $this->middleware('permission');
            $this->middleware('device.trust');
            
            $this->commentRepository = $commentRepository;

        }catch (\Exception $e) {
            return $this->handleException($e);
        }

    }


    public function index()
    {

        try {
            $comments  = $this->commentRepository->all();
            if($comments->count()==0)return $this->apiResponse($comments, true ,"No Comments found !" ,Response::HTTP_NOT_FOUND);
            return $this->apiResponse($comments, true, "Successfully retrieved " . $comments->count() . " Comments" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }

    }



    public function store(CommentStoreRequest $request)
    {
        try {
            $attributes = $request->validated();
            $comment = $this->commentRepository->store($attributes);
            return $this->apiResponse($comment , true, 'comment created successfully', Response::HTTP_CREATED);
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


                $comment  = $this->commentRepository->listByUserAndMenu($userId , $menuId)['comments'];
                $userName = $this->commentRepository->listByUserAndMenu($userId , $menuId)['userName'];
                $menuName = $this->commentRepository->listByUserAndMenu($userId , $menuId)['menuName'];
                if($comment->count()==0) {
                    return $this->apiResponse($comment, false, "No Comment were found for the [ ".$userName." ] user and the [ ".$menuName." ] menu.", Response::HTTP_NOT_FOUND);
                } else {
                    $commentCount = $comment->count();
                    return $this->apiResponse($comment, true, "Successfully retrieved ".$commentCount." Comment(s) for the [ ".$userName." ] user and the [ ".$menuName." ] menu.", Response::HTTP_OK);
                }
            }

            elseif (strpos($id, 'menu=') === 0) {
                $menuId = substr($id, strlen('menu='));
                $comment  = $this->commentRepository->listByMenu($menuId)['comments'];
                $menuName = $this->commentRepository->listByMenu($menuId)['menuName'];
                if($comment->count()==0) {
                    return $this->apiResponse($comment, false, "No Comment were found for the [ ".$menuName." ] menu.", Response::HTTP_NOT_FOUND);
                } else {
                    $commentCount = $comment->count();
                    return $this->apiResponse($comment, true, "Successfully retrieved ".$commentCount." Comment(s) for the [ ".$menuName." ] menu.", Response::HTTP_OK);
                }
            }

            elseif(strpos($id, 'user=') === 0){
                $userId = substr($id, strlen('user='));
                $comment  = $this->commentRepository->listByuser($userId)['comments'];
                $userName = $this->commentRepository->listByuser($userId)['userName'];
                if($comment->count()==0) {
                    return $this->apiResponse($comment, false, "No Comment were found for the [ ".$userName." ] user.", Response::HTTP_NOT_FOUND);
                } else {
                    $commentCount = $comment->count();
                    return $this->apiResponse($comment, true, "Successfully retrieved ".$commentCount." Comment(s) for the [ ".$userName." ] user.", Response::HTTP_OK);
                }
            }
            $comment  = $this->commentRepository->show($id);
            return $this->apiResponse($comment, true, "Successfully retrieved the Comment" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function update(CommentUpdateRequest $request, string $id)
    {
        try {
            $attributes = $request->all();
            $comment = $this->commentRepository->update($id, $attributes);
            return $this->apiResponse($comment, true, 'comment updated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(string $id)
    {
        try {
            if ($id == "all") {
                $this->commentRepository->clear();
                return $this->apiResponse(null, true, 'All Comments have been deleted successfully', Response::HTTP_OK);
            } else {
                $this->commentRepository->destroy($id);
                return $this->apiResponse(null, true, 'comment deleted successfully', Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
