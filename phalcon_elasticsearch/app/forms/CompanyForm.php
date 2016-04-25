<?php
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;

class CompanyForm extends Form
{
    public function initialize()
    {

        $this->add(new Text("name"));
        $this->add(new Text("categories"));
        $attr = array(
            'name' => 'groupName'
        );
        $sortName = new Check('sort_name', array(
            'value' => 1,
            'class' => 'box'
        ));
        $sortCategories = new Check('sort_categories', array(
            'value' => 1,
            'class' => 'box'
        ));
        $this->add($sortName);
        $this->add($sortCategories);

//            $this->add(
//                new Select(
//                    "companies",
//                    array(
//                        'H' => 'Home',
//                        'C' => 'Cell'
//                    )
//                )
//            );
    }
}