<?php

class exampleController extends Controller {

	public function index(){
		$examples=$this->model->load();		// просим у модели все записи
		$this->setResponce($examples);		// возвращаем ответ 
	}

	public function view($data){
		$example=$this->model->load($data['id']); // просим у модели конкретную запись
		$this->setResponce($example);
	}

	public function add(){
		if(isset($_POST['title'])){
			// мы передаем в модель массив с данными
			// модель должна вернуть boolean
			$dataToSave=array('title'=>$_POST['title']);
			$addedItem=$this->model->create($dataToSave);
			$this->setResponce($addedItem);
		}
	}

	public function edit($request){
		$_PUT = array ();
		parse_str(file_get_contents('php://input'), $_PUT);
        	
		if ((isset($request['id'])) && (isset($_PUT['title']))){
			$dataToUpdate = array('title' => $_PUT['title']);
			$updatedItem=$this->model->save($dataToUpdate, $request['id']);
			$this->setResponce($updatedItem);
		}
	}	

	public function delete($request){
		if (isset($request['id'])){
			$deletedItem=$this->model->delete($request['id']);
			$this->setResponce($deletedItem);
		}
	}
}