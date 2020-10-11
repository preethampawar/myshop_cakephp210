<?php
App::uses('AppModel', 'Model');
class Product extends AppModel {
    public $name = 'Product';
   
	var $belongsTo = array('ProductCategory', 'Brand');	
	
	var $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => false,
				'message' => 'Product name is a required field'
			),
			'between' => array(
				'rule' => array('between', 2, 100),
				'message' => 'Product name should be minimum of 2 characters and maximum of 100 characters'
			)
		)
	);
}