<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use App\Models\User;
use App\Models\Doc as Document;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Route::bind('document', function ($value) {
    $doc = Document::find($value);
    if ($doc) {
        return $doc;
    }

    $doc = Document::where('slug', $value)->first();
    if ($doc) {
        return $doc;
    }

    throw new NotFoundHttpException;
});

Route::bind('documentTrashed', function ($value) {
    $doc = Document::withTrashed()->find($value);
    if ($doc) {
        return $doc;
    }

    $doc = Document::withTrashed()->where('slug', $value)->first();
    if ($doc) {
        return $doc;
    }

    throw new NotFoundHttpException;
});


// Authentication
Auth::routes();


// Home page
Route::get('/', 'HomeController@index');


// Documents
Route::resource('documents', 'DocumentController');

Route::post('/documents/{document}/pages', 'DocumentController@storePage')
    ->name('documents.pages.store');

Route::get('/documents/{document}/images/{image}', 'DocumentController@showImage')
    ->name('documents.images.show');

Route::delete('/documents/{document}/images/{image}', 'DocumentController@destroyImage')
    ->name('documents.images.destroy');

Route::get('/documents/{documentTrashed}/restore', 'DocumentController@restore')
     ->name('documents.restore');


// Sponsors
Route::resource('sponsors', 'SponsorController');

Route::put('/sponsors/{sponsor}/status', 'SponsorController@updateStatus')
    ->name('sponsors.status.update');


// Users
Route::resource('users', 'UserController', ['only' => [
    'edit', 'update'
]]);
