<?php
namespace App\Http\Interfaces\Repository;

interface ReservationRepositoryInterface
{
    public function all();
    public function show($id);
    public function store($atributes);
    public function destroy($id);
}

