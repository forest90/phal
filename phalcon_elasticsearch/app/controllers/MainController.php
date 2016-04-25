<?php
use Phalcon\Mvc\Controller;

class MainController extends Controller
{
    protected $es;
    public function initialize()
    {
        $this->es = new Elasticsearch\Client([
            'hosts' => ['127.0.0.1:9200']
        ]);
    }

    public function getAll()
    {
        return $this->es->search();
    }

    protected function syncComapnyData($companies = [])
    {
        foreach($companies as $company) {
            try{
                $result = [];
                foreach($company as $field => $value){
                    if($field == 'CompanyCategory'){
                        $result[$field] = explode(' ', $value);
                    }else{
                        $result[$field]  = $value;
                    }
                }
                $params = [
                    'id' => $result['id'],
                    'index' => 'companies',
                    'type' => 'company',
                    'body' => $result
                ];

                $this->es->index($params);
            }catch (Exception $e){}

        }
    }

    public function searchCompanyData($name, $page = 0, $skip = 0, $sort = [])
    {
        $query = [
            'body' => [
                'query' =>[
                    'bool' => [
                        'should' => [
                            'match' => ['CompanyName' => $name]
                        ]
                    ]
                ],
                'fields' => [
                    'CompanyName',
                    'CompanyCategory'
                ],
            ],

        ];

        $total = $this->es->count($query)['count'];
        $query['size'] = 10;
        $query['from'] = $skip;
        $query['sort'] = [
            "CompanyName:{$sort['sort_name']}"
        ];
        $result = $this->es->search($query);

        return [$total, $result];

    }
}