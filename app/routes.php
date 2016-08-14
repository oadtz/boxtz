<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::bind('user', function ($username, $route) 
{
	$userService = new Service\UserService;

	try {
		if(!$user = $userService->findByUsername($username))
			throw new Exception\NotFoundException('User Not Found.');
	} catch (Exception $e) {
		App::abort(404);
	}

	return $user;
});


Route::bind('screener', function ($screenerId, $route) 
{
	$screenerService = new Service\ScreenerService;

	try {
		if(!$screener = $screenerService->findById($screenerId))
			throw new Exception\NotFoundException('Screener Not Found.');
	} catch (Exception $e) {
		App::abort(404);
	}

	return $screener;
});

Route::get('test', function ()
{
	$data = array(
		'text'	=>	'Hello world.'
	);

	Mail::send('emails.test', $data, function($message)
	{
	    $message->to('t.pirmphol@gmail.com', 'Thanapat Pirmphol')->subject('Welcome!');
	});
});

Route::group(['prefix' => 'api', 'before' => 'api'], function ()
{
	Route::controller('auth', 'Api\AuthController');
	Route::resource('company', 'Api\CompanyController');
	Route::resource('index', 'Api\IndexController');
	Route::resource('user', 'Api\UserController');
	Route::get('screener/{screener}/stocks', 'Api\ScreenerController@getStocks');
	Route::resource('screener', 'Api\ScreenerController');
	Route::controller('user/{user?}', 'Api\UserController');
});

Route::controller('error', 'ErrorController');

Route::group(['before' => 'browser'], function () 
{
	Route::resource('screener', 'ScreenerController');
	Route::controller('user/{user?}', 'UserController');
	Route::controller('/', 'SiteController');
});