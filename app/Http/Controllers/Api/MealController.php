<?php

namespace App\Http\Controllers\Api;

use App\cloud\MediaCloud;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;




class MealController extends Controller
{

    public function index()
    {

        return response(response("hellow"));



    }



    public function store(Request $request)
    {
        return response(response('hellow'));
    }


    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
