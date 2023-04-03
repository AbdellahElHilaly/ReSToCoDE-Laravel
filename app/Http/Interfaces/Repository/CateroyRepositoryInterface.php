<?php
namespace App\Http\Interfaces\Repository;

interface CateroyRepositoryInterface
{
    public function all();
    public function show($id);
    public function clear();
    public function store($atributes);
    public function destroy($id);
    public function update($id , $atributes);

}

