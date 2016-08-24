<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::auth();

Route::get('/home', 'HomeController@index');

    /* -- Admin related Routes -- */

    //Login & Logout Routes...
    Route::get('/admin/login','AdminAuth\AuthController@showLoginForm');
    Route::post('/admin/login','AdminAuth\AuthController@login');
    Route::get('/admin/logout','AdminAuth\AuthController@logout');

    // Registration Routes...
    Route::get('/admin/register', 'AdminAuth\AuthController@showRegistrationForm');
    Route::post('/admin/register', 'AdminAuth\AuthController@register');

    // Password Reset Routes...
    Route::post('admin/password/email','AdminAuth\PasswordController@sendResetLinkEmail');
    Route::post('admin/password/reset','AdminAuth\PasswordController@reset');
    Route::get('admin/password/reset/{token?}','AdminAuth\PasswordController@showResetForm');

    // Admin Area Routes...
    Route::get('/admin', 'AdminController@index');

    Route::get('/admin/catalogs', 'AdminCatalogsController@index');
    Route::get('/admin/catalogs/new', 'AdminCatalogsController@new');
    Route::post('/admin/catalogs/new', 'AdminCatalogsController@create');
    Route::get('/admin/catalogs/{catalog}', 'AdminCatalogsController@edit');
    Route::patch('/admin/catalogs/{catalog}', 'AdminCatalogsController@update');
    Route::delete('/admin/catalogs/{catalog}', 'AdminCatalogsController@delete');

    Route::get('/admin/catalogs/{catalog}/pages', 'AdminPagesController@index');
    Route::get('/admin/catalogs/{catalog}/pages/new', 'AdminPagesController@new');
    Route::post('/admin/catalogs/{catalog}/pages/new', 'AdminPagesController@create');
    Route::get('/admin/catalogs/{catalog}/pages/{page}', 'AdminPagesController@edit');
    Route::patch('/admin/catalogs/{catalog}/pages/{page}', 'AdminPagesController@update');
    Route::patch('/admin/catalogs/{catalog}/pages/{page}/reposition', 'AdminPagesController@reposition');
    Route::get('/admin/catalogs/{catalog}/pages/{page}/images', 'AdminPagesController@manage_images');
    Route::post('/admin/catalogs/{catalog}/pages/{page}/images', 'AdminPagesController@add_image');
    Route::delete('/admin/catalogs/{catalog}/pages/{page}', 'AdminPagesController@delete');
    Route::post('/admin/catalogs/{catalog}/pages/{page}/picture/{picture}', 'AdminPagesController@link_image_to_page');
    Route::put('/admin/catalogs/{catalog}/pages/{page}/picture/{picture}', 'AdminPagesController@update_link_image_to_page');

    Route::post('/admin/catalogs/{catalog}/pages/{page}/picture/{picture}/unlink', 'AdminPagesController@unlink_image_to_page');

    Route::delete('/admin/catalogs/{catalog}/pages/{page}/picture/{picture}', 'AdminPagesController@delete_image');

    // API ROUTES

    Route::post('api/v1/login', 'ApiV1LoginController@authenticate');
    Route::get('api/v1/login', 'ApiV1LoginController@retrive_login');
    Route::put('api/v1/login', 'ApiV1LoginController@regenerate');
    Route::delete('api/v1/login', 'ApiV1LoginController@logout');

    Route::group(['prefix' => 'api/v1', 'middleware' => 'jwt-auth'], function () {

      Route::get('/users/{id}', 'ApiV1UsersController@check_user');
      Route::post('/users', 'ApiV1UsersController@create_user');
      Route::delete('/users/{id}', 'ApiV1UsersController@delete_user');

      Route::get('/pictures', 'ApiV1PicturesController@retrieve_all');
      Route::get('/pictures/{id}', 'ApiV1PicturesController@retrieve');
      Route::post('/pictures', 'ApiV1PicturesController@create');
      Route::put('/pictures/{id}', 'ApiV1PicturesController@update'); // must use x-www-form-urlencoded to test on post
      Route::delete('/pictures/{id}', 'ApiV1PicturesController@delete');
      Route::post('/pictures/{id}/upload', 'ApiV1PicturesController@upload');
      Route::get('/pictures/{id}/download', 'ApiV1PicturesController@download');


    });
