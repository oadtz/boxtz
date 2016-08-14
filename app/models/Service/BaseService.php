<?php namespace Service;

use Validator;
use Exception\PermissionException;
use Exception\ValidationException;

class BaseService {

	public function findById($id, $cache = null)
	{
		throw new PermissionException('Method not implemented.');
	}

	public function query($query = null, $search = null, $orderBy = null, $paging = null, $cache = null)
	{
		throw new PermissionException('Method not implemented.');
	}

	public function store($data)
	{
		throw new PermissionException('Method not implemented.');
	}

	public function update($id, $data)
	{
		throw new PermissionException('Method not implemented.');
	}
	
	public function destroy($id)
	{
		throw new PermissionException('Method not implemented.');
	}

	public function instance()
	{
		throw new PermissionException('Method not implemented.');
	}

	public function validate($data, $rules)
	{
		$validator = Validator::make($data, $rules);

		if($validator->fails()) 
			throw new ValidationException($validator);
		
		return true;
	}

}
	