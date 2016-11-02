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

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

    Route::bind( 'zizpic' , function($value , $route) {
        return App\Zizpic::find( $value );
    } );

Route::resource( 'zizpic' , 'ZizpicController' , [
    'names' => [
        'edit'    => 'zizpic.edit' ,
        'show'    => 'zizpic.show' ,
        'destroy' => 'zizpic.destroy' ,
        'update'  => 'zizpic.update' ,
        'store'   => 'zizpic.store' ,
        'index'   => 'zizpic' ,
        'create'  => 'zizpic.create' ,
    ]
] );
