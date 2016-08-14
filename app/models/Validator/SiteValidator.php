<?php namespace Validator;

use Auth;
use MongoId;
use MongoRegex;
use Site;
use Screener;
use User;
use Illuminate\Validation\Validator;

class SiteValidator extends Validator {


    public function validateUserScreenerUnique($attribute, $value, $parameters)
    {
        return (Screener::withTrashed()
                            ->where('owner._id', new MongoId(Auth::user()->id))
                            ->where('name', new MongoRegex('/^' . preg_quote(trim($value)) . '$/i'))
                            ->count() == 0);
    }

    public function validateUserUsernameUnique($attribute, $value, $parameters)
    {
    	if (!in_array(strtolower($value), array('admin', 'administrator', 'system', 'webmaster')))
    		return (User::withTrashed()->where('username', new MongoRegex('/^' . preg_quote(trim($value)) . '$/i'))->count() == 0);

    	return false;
    }

    public function validateUserEmailUnique($attribute, $value, $parameters)
    {
    	return (User::withTrashed()->where('email', new MongoRegex('/^' . preg_quote(trim($value)) . '$/i'))->count() == 0);
    }

    public function validateUserLoginExists($attribute, $value, $parameters)
    {
        if (User::withTrashed()->where('username', new MongoRegex('/^' . preg_quote(trim($value)) . '$/i'))->count() > 0 
            || User::withTrashed()->where('email', new MongoRegex('/^' . preg_quote(trim($value)) . '$/i'))->count() > 0)
            return true;

        return false;
        //return !$this->validateUserUsernameUnique($attribute, $value, $parameters) || !$this->validateUserEmailUnique($attribute, $value, $parameters);
    }

    public function validateUserUsernameExists($attribute, $value, $parameters)
    {
        return !$this->validateUserUsernameUnique($attribute, $value, $parameters);
    }

}