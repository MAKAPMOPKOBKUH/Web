<?php

class userController extends Controller {

	public function index(){
		$examples=$this->model->load();		// просим у модели все записи
		$this->setResponce($examples);		// возвращаем ответ 
	}

	public function view($data){
		$example=$this->model->load($data['id']); // просим у модели конкретную запись
		$this->setResponce($example);
	}

	public function add(){
    $data=json_decode(file_get_contents('php://input'), TRUE);

    if ((isset($data['id'])) && (isset($data['name'])) && (isset($data['score']))){
      // мы передаем в модель массив с данными
      // модель должна вернуть boolean
      $dataToSave=array('id'=>$data['id'], 'name' => $data['name'], 'score' => $data['score']);
      $addedItem=$this->model->create($dataToSave);
      $this->setResponce($addedItem);
    }
  }

	public function edit($request){
		$_PUT = array ();
		parse_str(file_get_contents('php://input'), $_PUT);
        	
		if ((isset($_PUT['id'])) && (isset($_PUT['name'])) && (isset($_PUT['score']))){
			$dataToUpdate = array('id'=>$_PUT['id'], 'name' => $_PUT['name'], 'score' => $_PUT['score']);
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