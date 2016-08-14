<?php namespace Api;

use Response;
use Service\IndexService;

class IndexController extends BaseController {
	
	public function __construct(IndexService $indexService)
	{
		parent::__construct();

		$this->indexService = $indexService;
	}

	public function index()
	{
		$result = $this->indexService->query($this->getQuery(), $this->getSearch(), $this->getOrder(), $this->getPaging(), true, 1);

		return Response::json($result['Rows'], 200, array('TotalRows' => $result['TotalRows']));
	}

}