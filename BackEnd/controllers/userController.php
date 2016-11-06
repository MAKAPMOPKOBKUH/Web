<?php

class userController extends Controller {

	public function index(){
		$examples=$this->model->load();		// ������ � ������ ��� ������
		$this->setResponce($examples);		// ���������� ����� 
	}

	public function view($data){
		$example=$this->model->load($data['id']); // ������ � ������ ���������� ������
		$this->setResponce($example);
	}

	public function add(){
		if ((isset($_POST['id'])) && (isset($_POST['name'])) && (isset($_POST['score']))){
			// �� �������� � ������ ������ � �������
			// ������ ������ ������� boolean
			$dataToSave=array('id'=>$_POST['id'], 'name' => $_POST['name'], 'score' => $_POST['score']);
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