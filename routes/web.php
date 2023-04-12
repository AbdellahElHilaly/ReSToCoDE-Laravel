<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\MediaController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('Mails/registerVerification');
    return view('welcome');
});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/media/{location}/{filename}', [MediaController::class, 'show'])->where('location', 'images|videos|audios');

