<?php
namespace App\Http\Repositories;
use App\Models\Menu;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use App\Helpers\Auth\AuthHelper;
use App\Http\Resources\CommentResource;
use App\Http\Interfaces\Repository\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface{

    use AuthHelper;

    public function all()
    {
        return CommentResource::collection(Comment::all());
    }

    public function show($id)
    {
        $comment = Comment::findOrFail($id);
        return new CommentResource($comment);
    }

    public function store($attributes)
    {
        $attributes['user_id'] = $this->getAuthUser()->id;
        $comments = Comment::create($attributes);
        return new CommentResource($comments);
    }


    public function clear()
    {
        $this->checkEmpty();
        Comment::truncate();
    }


    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
    }

    public function update($id , $attributes)
    {
        $attributes['user_id'] = $this->getAuthUser()->id;
        $comment = Comment::findOrFail($id);
        $comment->update($attributes);
        return new CommentResource($comment);
    }

    public function listByMenu($menuId){

        $menus = Menu::with('comments')->findOrFail($menuId);
        $comments = CommentResource::collection($menus->Comments);

        $data['menuName'] = $menus->name;
        $data['comments'] = $comments;
        return $data;
    }

    public function listByuser($userId){

        $comments = Comment::where('user_id' , $userId)->get();
        $data['comments'] = CommentResource::collection($comments);

        $user = User::findOrFail($userId);
        $data['userName'] = $user->name;
        return $data;
    }

    public function listByUserAndMenu($userId, $menuId)
    {
        $menu = Menu::findOrFail($menuId);
        $comments = $menu->comments()->where('user_id', $userId)->get();

        $data['menuName'] = $menu->name;
        $data['comments'] = CommentResource::collection($comments);

        $user = User::findOrFail($userId);
        $data['userName'] = $user->name;

        return $data;
    }



    public function checkEmpty(){
        if (Comment::count() === 0) {
            throw new \Exception('SYSTEM ERROR: Comment table is already empty');
        }
    }

}

