<?php namespace Api;

use Response;
use Service\CompanyService;

class CompanyController extends BaseController {
	
	public function __construct(CompanyService $companyService)
	{
		parent::__construct();

		$this->companyService = $companyService;
	}

	public function index()
	{
		$result = $this->companyService->query($this->getQuery(), $this->getSearch(), $this->getOrder(), $this->getPaging(), true, 1);

		return Response::json($result['Rows'], 200, array('TotalRows' => $result['TotalRows']));
	}

}