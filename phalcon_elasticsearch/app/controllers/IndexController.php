<?php


class IndexController extends MainController
{

	public function indexAction()
	{
		$this->view->companies = $this->getAll()["hits"]["hits"];

		$this->assets
            ->addCss('bower_components/dist/css/bootstrap.min.css');

	}

	public function syncAction()
	{
		$companies = Company::find(['limit' => 10000]);
		$this->syncComapnyData($companies);
		var_dump('MySql -> elasticSearch synced!');
		$response = new \Phalcon\Http\Response();
		return $response->redirect();
	}

}
