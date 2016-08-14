<?php namespace Api;

use Auth;
use Input;
use Response;
use Service\CompanyService;
use Service\ScreenerService;

class ScreenerController extends BaseController {
	
	public function __construct(ScreenerService $screenerService, CompanyService $companyService)
	{
		parent::__construct();

		$this->screenerService = $screenerService;
		$this->companyService = $companyService;

		$this->beforeFilter('auth');
	}

	public function store()
	{
		return Response::json($this->screenerService->store(Input::all()));
	}

	public function update($screener)
	{
		if ($screener->owner['id'] != Auth::user()->id)
			throw Exception\PermissionException('Permission Denied.');

		return Response::json($this->screenerService->update($screener->id, Input::all()));
	}

	public function destroy($screener)
	{
		if ($screener->owner['id'] != Auth::user()->id)
			throw Exception\PermissionException('Permission Denied.');

		return Response::json($this->screenerService->destroy($screener->id));
	}

	public function getStocks($screener)
	{
		if ($screener->owner['id'] != Auth::user()->id)
			throw Exception\PermissionException('Permission Denied.');

		/*$q = [];

		foreach ($screener->filters as $filter)
		{
			if (!empty($filter['name']))
			{
				if (!empty($filter['min']))
					$q[$filter['name']] = [
						'$gte'		=>	$filter['min']
					];

				if (!empty($filter['max']))
					$q[$filter['name']] = [
						'$lte'		=>	$filter['max']
					];
			}
		}

		$result = $this->companyService->query($q, null, ['Symbol' => 'asc'], null, true, 1);

		return Response::json($result['Rows'], 200, array('TotalRows' => $result['TotalRows']));*/

		$result = $this->screenerService->getStocks($screener->id);

		return Response::json($result['Rows'], 200, array('TotalRows' => $result['TotalRows']));
	}

}