<?php
namespace App\Http\Repositories;
use App\Models\Game;
use App\Models\User;
use App\Models\Category;
use App\Http\Resources\GameResource;
use App\Http\Interfaces\Repository\GameRepositoryInterface;

class GameRepository implements GameRepositoryInterface{

    public function all()
    {
        return GameResource::collection(Game::all());
    }

    public function show($id)
    {
        $game = Game::findOrFail($id);
        return new GameResource($game);
    }

    public function store($attributes)
    {
        $attributes['user_id'] = auth()->user()->id;
        $games = Game::create($attributes);
        return new GameResource($games);
    }


    public function clear()
    {
        $this->checkEmpty();
        Game::truncate();
    }


    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();
    }

    public function update($id , $attributes)
    {
        $attributes['user_id'] = auth()->user()->id;
        $game = Game::findOrFail($id);
        $game->update($attributes);
        return new GameResource($game);
    }

    public function listByCategory($categoryId){

        // $categoryName = Category::findOrFail($categoryId)->name;
        // $games = Game::where('category_id', $categoryId)->get();

        $category = Category::with('games')->findOrFail($categoryId);

        $games = GameResource::collection($category->games);

        $data['categoryName'] = $category->name;
        $data['games'] = $games;
        return $data;
    }

    public function listByUser($userId){

        $userId = auth()->user()->id ? $userId=="auth" : User::findOrFail($userId)->id;
        $userName = User::findOrFail($userId)->name;
        $games = Game::where('user_id', $userId)->get();

        $data['userName'] = $userName;
        $data['games'] = GameResource::collection($games);

        return $data;
    }



    public function checkEmpty(){
        if (Game::count() === 0) {
            throw new \Exception('SYSTEM ERROR: Game table is already empty');
        }
    }

}

