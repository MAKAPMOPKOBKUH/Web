<?php

class menuController extends Controller {

	public function index(){
		$examples=$this->model->load();		// ������ � ������ ��� ������
		$this->setResponce($examples);		// ���������� ����� 
	}

	public function view($data){
		$example=$this->model->load($data['id']); // ������ � ������ ���������� ������
		$this->setResponce($example);
	}

}