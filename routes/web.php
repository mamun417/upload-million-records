<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', function () {
    return view('upload-file');
});

Route::post('/upload', function () {

    if (request()->has('mycsv')) {
        $data = array_map('str_getcsv', file(request()->mycsv));
        $header = $data[0];
        unset($data[0]);

        return $header;
    }

    return 'File not found';
});
