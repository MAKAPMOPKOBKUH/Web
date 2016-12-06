<?php

class Model{

	// здесь будем хранить название файла данных для этой модель
	public $dataFileName;

	public function __construct($modelName){
		// конструируем название файла данных
		// должно получиться примерно /data/modelname.json
		$this->dataFileName=DATA_FOLDER.DS.$modelName.'.json';
	}

	public function create(array $item){
		// считываем нашу "базу данных"
		$data=file_get_contents($this->dataFileName);
		// декодируем
		$data=json_decode($data, true);
		// добавляем элемент
		array_push($data, $item);
		// сохраняем файл, и возврfщаем результат сохранения (успех или провал)
		return file_put_contents($this->dataFileName, json_encode($data));
	}

	public function load($id=false){
		// считаем файл
		$data=file_get_contents($this->dataFileName);
		// декодируем 
		$data=json_decode($data, true);

		// если id не передан - то возвращаем все записи, иначе только нужную
		if($id===false){
			return $data;
		}
		else{
			foreach ($data as $value) {
				if ($value['id'] == $id){
					return $value;
				}
			}
		}
		return false;
	}

	public function save(array $newItem, $id){

		$data=file_get_contents($this->dataFileName);
		// декодируем
		$data=json_decode($data, true);

		foreach ($data as $key => $value) {
				if ($value['id'] == $id){
					$value = $newItem;
				}
		}		
		return file_put_contents($this->dataFileName, json_encode($data));
	}

	public function delete($id){

		$data=file_get_contents($this->dataFileName);
		// декодируем 
		$data=json_decode($data, true);

		foreach ($data as $key => $value) {
				if ($value['id'] == $id){
					unset($data[$key]);
				}
		}
		return file_put_contents($this->dataFileName, json_encode($data));
	}
}