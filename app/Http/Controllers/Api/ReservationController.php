<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Helpers\ApiResponceHandler;
use App\Http\Controllers\Controller;
use App\Exceptions\ErorrExeptionsHandler;
use App\Http\Requests\Reservation\ReservationStoreRequest;
use App\Http\Interfaces\Repository\ReservationRepositoryInterface;




class ReservationController extends Controller
{
    protected ReservationRepositoryInterface $reservationRepository;

    use ErorrExeptionsHandler;
    use ApiResponceHandler;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        try{
            $this->middleware('auth');
            $this->middleware('account.verified');
            $this->middleware('permission');
            $this->middleware('device.trust');
            
            $this->reservationRepository = $reservationRepository;

        }catch (\Exception $e) {
            return $this->handleException($e);
        }

    }


    public function index()
    {
        try {
            $reservations  = $this->reservationRepository->all();
            if($reservations->count()==0)return $this->apiResponse($reservations, true ,"No Reservations found !" ,Response::HTTP_NOT_FOUND);
            return $this->apiResponse($reservations, true, "Successfully retrieved " . $reservations->count() . " Reservations" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }

    }



    public function store(ReservationStoreRequest $request)
    {
        try {
            $attributes = $request->validated();
            $Reservation = $this->reservationRepository->store($attributes);
            return $this->apiResponse($Reservation , true, 'Reservation created successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



    public function show(string $id)
    {
        try {
            $Reservation  = $this->reservationRepository->show($id);
            return $this->apiResponse($Reservation, true, "Successfully retrieved the Reservation" , Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }


    public function destroy(string $id)
    {
        try {
            $this->reservationRepository->destroy($id);
            return $this->apiResponse(null, true, 'Reservation deleted successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }



}
