<?php

App::uses('AppController', 'Controller');

class FileManagerAppController extends AppController {

	public $uses = array();

	public $components = array('RequestHandler');

	public $helpers = array('Js');

	public function beforeFilter() {
		$this->Auth->allow();
	}

}
