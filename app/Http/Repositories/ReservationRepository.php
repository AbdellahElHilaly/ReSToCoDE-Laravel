<?php
namespace App\Http\Repositories;
use App\Models\Reservation;
use App\Models\User;
use App\Models\FeedBack;
use App\Models\Category;
use App\Helpers\Auth\AuthHelper;
use App\Http\Resources\ReservationResource;
use App\Http\Interfaces\Repository\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface{

    use AuthHelper;

    public function all(){
        return ReservationResource::collection(Reservation::all());
    }
    public function show($id){

    }

    public function store($atributes){



        $today = date("Y-m-d");
        $atributes['date'] = $today;
        $atributes['user_id'] = $this->getAuthUser()->id;
        if($this->alredyReserved('null' , $atributes['user_id']))
            throw new \Exception('SYSTEM_CLIENT_ERROR : you have already reserved');

        $reservation = Reservation::create($atributes);
        return new ReservationResource($reservation);
    }

    public function destroy($id){
        $user_id = $this->getAuthUser()->id;

        if(!$this->alredyReserved($id , $user_id))
            throw new \Exception('SYSTEM_CLIENT_ERROR : you have not reserved yet');

        if($id === 'null')
            Reservation::where('user_id' , $user_id)->delete();
        else
            Reservation::where('id' , $id)->delete();
    }

    private function alredyReserved($id , $user_id){
        if($id === 'null'){
            $reservation = Reservation::where('user_id' , $user_id)->first();
            return true ? $reservation : false;

        }
        else{
            $reservation = Reservation::where('id' , $id)->first();
            return true ? $reservation : false;
        }

    }


}

