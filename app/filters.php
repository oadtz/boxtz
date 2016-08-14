<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{

	// Register Validators
	Validator::resolver(function($translator, $data, $rules, $messages)
	{
	    return new Validator\SiteValidator($translator, $data, $rules, $messages);
	});
	DB::connection()->disableQueryLog();

});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/
Route::filter('api', function ()
{
	Config::set('api', true);
});

Route::filter('browser', function ()
{
	$browser = new Ikimea\Browser\Browser();
	if($browser->getBrowser() == Ikimea\Browser\Browser::BROWSER_IE && $browser->getVersion() <= 8) {
	    throw new Exception\UnsupportedBrowserException('Please update your browser.');
	}
});

Route::filter('auth', function()
{
	if (Auth::guest()) {
  		if (Config::get('api') || Request::ajax())
			throw new Exception\LoginException('Log In Required.', 401);
			
		return Redirect::guest('signin');
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});