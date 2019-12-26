<?php
class View extends Orm_Magic {
    
        /**
         *
         * @var type 
         */
	protected $_path = APP_DIR;
        
        /**
         * Установка корневого каталога темы 
         * @param type $path
         */
	public function setRenderPath($path) {
		$this->_path = $path;
	}
	/**
         * Получение корневого каталога темы 
         * @return type
         */
	public function getRenderPath() {
		return $this->_path;
	}
	/**
         * Чтение файла для представления данных
         * @param type $filename - имя файла который нужно прочитать
         * @return type - представление данных
         * @throws Exception - сообщение ошибки, в случаи отсутсвия файла
         */
	function render($filename) {
		$result = '';
		$fn = $this->getRenderPath() .'/views/' . $filename;

		if(file_exists($fn) && !is_dir($fn)) {
			ob_start();
			include($fn);
			/*eval("?>" . @file_get_contents($fn) . "<?");*/
			
			$result = ob_get_contents();
			ob_end_clean();
		} else {
			throw new Exception("Template file $filename not found ($fn)");
		}

		return $result;
	}
	/**
         * Просмотр файла
         * @param type $filename - имя файла
         */
	function show($filename) {
		echo $this->render($filename);
	}

}
