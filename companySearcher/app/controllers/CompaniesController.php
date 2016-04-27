<?php
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;
use Phalcon\Mvc\Url;

class CompaniesController extends MainController
{

	public function searchAction()
	{
        if ($this->request->isGet() && $this->request->get('name'))
        {
            $currentPage = $this->request->get('page') ? : 0;
            $skip = $currentPage * 10;

            $name = $this->request->get('name');
            $limit = $this->request->get('limit') ? : null;
            $sort['sort_name'] = $this->request->get('sort_name') ? 'asc' : 'desc';
            list($total, $result) = $this->searchCompanyData($name, $currentPage, $skip, $sort);

			if($result['hits']['total'] >= 1)
			{
                $this->view->companies = $result['hits']['hits'];
                $this->view->total = $total;
                $this->view->skip = $skip;
                $this->view->currentPage = $currentPage;
                $this->view->uri = "/companies/search?name={$name}&sort_name={$this->request->get('sort_name')}&page=";
			}
		}
        $this->view->name = $name ? : '';
        $this->view->sort_name = $this->request->get('sort_name') ? 1 : 0;

	}

	public function saveAction()
	{
		$this->view->companies = $this->getAll();
	}


}
